<?php

namespace DahRomy\MVola\Service\Auth;

use DahRomy\MVola\Exception\MVolaApiException;
use DahRomy\MVola\Exception\MVolaAuthenticationException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class MVolaAuthenticator implements MVolaAuthenticatorInterface
{
    private ClientInterface $client;
    private string $authUrl;
    private string $consumerKey;
    private string $consumerSecret;
    private CacheInterface $cache;
    private int $cacheTtl;

    public function __construct(
        ClientInterface $client,
        string          $authUrl,
        string          $consumerKey,
        string          $consumerSecret,
        CacheInterface  $cache,
        int             $cacheTtl
    )
    {
        $this->client = $client;
        $this->authUrl = $authUrl;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->cache = $cache;
        $this->cacheTtl = $cacheTtl;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function authenticate(): array
    {
        return $this->cache->get('mvola_auth_token', function (ItemInterface $item) {
            $token = $this->fetchNewToken();
            $item->expiresAfter(min($token['expires_in'] ?? $this->cacheTtl, $this->cacheTtl));
            return $token;
        });
    }

    /**
     * @throws MVolaAuthenticationException|MVolaApiException
     */
    private function fetchNewToken(): array
    {
        try {
            $response = $this->client->request('POST', $this->authUrl, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->consumerKey . ':' . $this->consumerSecret),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cache-Control' => 'no-cache',
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'scope' => 'EXT_INT_MVOLA_SCOPE',
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (!isset($result['access_token'])) {
                throw new MVolaAuthenticationException("Failed to obtain access token");
            }

            return $result;
        } catch (GuzzleException $e) {
            throw new MVolaAuthenticationException("Authentication failed: " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
