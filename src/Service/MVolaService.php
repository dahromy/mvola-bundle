<?php

namespace DahRomy\MVola\Service;

use DahRomy\MVola\Model\TransactionRequest;
use DahRomy\MVola\Service\ApiClient\MVolaApiClientInterface;
use DahRomy\MVola\Service\Auth\MVolaAuthenticatorInterface;
use DahRomy\MVola\Service\Transaction\MVolaTransactionServiceInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class MVolaService
{
    private MVolaApiClientInterface $apiClient;
    private MVolaAuthenticatorInterface $authenticator;
    private MVolaTransactionServiceInterface $transactionService;
    private RouterInterface $router;

    public function __construct(
        MVolaApiClientInterface $apiClient,
        MVolaAuthenticatorInterface $authenticator,
        MVolaTransactionServiceInterface $transactionService,
        RouterInterface $router
    ) {
        $this->apiClient = $apiClient;
        $this->authenticator = $authenticator;
        $this->transactionService = $transactionService;
        $this->router = $router;
    }

    public function authenticate(): void
    {
        $authResult = $this->authenticator->authenticate();
        $this->apiClient->setAccessToken($authResult['access_token']);
    }

    public function initiateTransaction(TransactionRequest $request): array
    {
        $this->authenticate();

        // Automatically set the callback URL
        $callbackData = $request->getCallbackData();
        $callbackUrl = $this->getCallbackUrl($callbackData);

        $request->setCallbackUrl($callbackUrl);

        return $this->transactionService->initiateTransaction($request);
    }

    public function getTransactionStatus(string $serverCorrelationId): array
    {
        $this->authenticate();
        return $this->transactionService->getTransactionStatus($serverCorrelationId);
    }

    public function getTransactionDetails(string $transactionId): array
    {
        $this->authenticate();
        return $this->transactionService->getTransactionDetails($transactionId);
    }

    public function getCallbackUrl(?array $callbackData = []): string
    {
        $encodedCallbackData = base64_encode(json_encode($callbackData));
        return $this->router->generate('mvola_callback', ['data' => $encodedCallbackData], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
