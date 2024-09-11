<?php

namespace DahRomy\Mvola\Service\Transaction;

use DahRomy\Mvola\Model\TransactionRequest;
use DahRomy\Mvola\Service\ApiClient\MVolaApiClientInterface;


class MVolaTransactionService implements MVolaTransactionServiceInterface
{
    private MVolaApiClientInterface $apiClient;

    public function __construct(MVolaApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function initiateTransaction(TransactionRequest $request): array
    {
        $endpoint = '/mvola/mm/transactions/type/merchantpay/1.0.0/';
        return $this->apiClient->sendRequest('POST', $endpoint, $request->toArray());
    }

    public function getTransactionStatus(string $serverCorrelationId): array
    {
        $endpoint = "/mvola/mm/transactions/type/merchantpay/1.0.0/status/{$serverCorrelationId}";
        return $this->apiClient->sendRequest('GET', $endpoint);
    }

    public function getTransactionDetails(string $transactionId): array
    {
        $endpoint = "/mvola/mm/transactions/type/merchantpay/1.0.0/{$transactionId}";
        return $this->apiClient->sendRequest('GET', $endpoint);
    }
}
