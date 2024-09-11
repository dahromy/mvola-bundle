<?php

namespace DahRomy\Mvola\Service\Transaction;

use DahRomy\Mvola\Model\TransactionRequest;

interface MVolaTransactionServiceInterface
{
    public function initiateTransaction(TransactionRequest $request): array;
    public function getTransactionStatus(string $serverCorrelationId): array;
    public function getTransactionDetails(string $transactionId): array;
}
