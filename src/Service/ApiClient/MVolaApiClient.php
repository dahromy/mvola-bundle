<?php

namespace DahRomy\Mvola\Service\ApiClient;

use DahRomy\Mvola\Exception\MVolaApiException;
use DahRomy\Mvola\Exception\MVolaNetworkException;
use DahRomy\Mvola\Exception\MVolaRateLimitException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class MVolaApiClient implements MVolaApiClientInterface
{
    private ClientInterface $client;
    private string $baseUrl;
    private string $accessToken;
    private string $merchantNumber;
    private string $companyName;

    public function __construct(
        ClientInterface $client,
        string          $baseUrl,
        string          $merchantNumber,
        string          $companyName
    )
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->merchantNumber = $merchantNumber;
        $this->companyName = $companyName;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @throws MVolaApiException
     */
    public function sendRequest(string $method, string $endpoint, array $data = []): array
    {
        try {
            $headers = $this->getHeaders($data);

            unset($data['callbackUrl']);
            unset($data['callbackData']);

            $response = $this->client->request($method, $this->baseUrl . $endpoint, [
                'headers' => $headers,
                'json' => $data,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleException($e);
        }
    }


    private function getHeaders(array $data): array
    {
        return [
            'Authorization' => "Bearer {$this->accessToken}",
            'Version' => '1.0',
            'X-CorrelationID' => $this->generateUuid(),
            'UserLanguage' => 'MG',
            'UserAccountIdentifier' => "msisdn;{$this->merchantNumber}",
            'partnerName' => $this->companyName,
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-cache',
            'X-Callback-URL' => $data['callbackUrl'] ?? null,
        ];
    }

    /**
     * @throws MVolaRateLimitException
     * @throws MVolaApiException
     * @throws MVolaNetworkException
     */
    private function handleException(GuzzleException $e): void
    {
        if ($e->getCode() === 429) {
            throw new MVolaRateLimitException("Rate limit exceeded", $e->getCode(), $e);
        }

        if ($e->getCode() >= 500) {
            throw new MVolaNetworkException("MVola API network error", $e->getCode(), $e);
        }

        throw new MVolaApiException("MVola API request failed: " . $e->getMessage(), $e->getCode(), $e);
    }

    private function generateUuid(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
