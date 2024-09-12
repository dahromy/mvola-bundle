<?php

namespace DahRomy\MVola\Service\Transaction;

use DahRomy\MVola\Model\TransactionRequest;

interface MVolaTransactionServiceInterface
{
    public function initiateTransaction(TransactionRequest $request): array;
    public function getTransactionStatus(string $serverCorrelationId): array;
    public function getTransactionDetails(string $transactionId): array;
}
