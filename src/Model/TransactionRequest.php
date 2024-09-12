<?php

namespace DahRomy\MVola\Model;

use DahRomy\MVola\Exception\MVolaApiException;
use DahRomy\MVola\Exception\MVolaValidationException;

class TransactionRequest
{
    private string $amount;
    private string $currency;
    private string $descriptionText;
    private string $requestingOrganisationTransactionReference;
    private \DateTime $requestDate;
    private ?string $originalTransactionReference;
    private array $debitParty;
    private array $creditParty;
    private ?array $metadata = [];
    private ?string $callbackUrl = null;
    private ?array $callbackData = [];

    /**
     * @throws MVolaApiException
     */
    public function toArray(): array
    {
        $this->validate();
        return array_filter([
            'amount' => $this->amount,
            'currency' => $this->currency,
            'descriptionText' => $this->descriptionText,
            'requestingOrganisationTransactionReference' => $this->requestingOrganisationTransactionReference,
            'requestDate' => $this->requestDate->format('Y-m-d\TH:i:s.v\Z'),
            'originalTransactionReference' => $this->originalTransactionReference,
            'debitParty' => $this->debitParty,
            'creditParty' => $this->creditParty,
            'metadata' => $this->metadata,
            'callbackUrl' => $this->callbackUrl,
            'callbackData' => $this->callbackData,
        ], fn($value) => $value !== null);
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getDescriptionText(): string
    {
        return $this->descriptionText;
    }

    public function setDescriptionText(string $descriptionText): void
    {
        $this->descriptionText = $descriptionText;
    }

    public function getRequestDate(): \DateTime
    {
        return $this->requestDate;
    }

    public function setRequestDate(\DateTime $requestDate): void
    {
        $this->requestDate = $requestDate;
    }

    public function getDebitParty(): array
    {
        return $this->debitParty;
    }

    public function setDebitParty(array $debitParty): void
    {
        $this->debitParty = $debitParty;
    }

    public function getCreditParty(): array
    {
        return $this->creditParty;
    }

    public function setCreditParty(array $creditParty): void
    {
        $this->creditParty = $creditParty;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getRequestingOrganisationTransactionReference(): string
    {
        return $this->requestingOrganisationTransactionReference;
    }

    public function setRequestingOrganisationTransactionReference(string $requestingOrganisationTransactionReference): void
    {
        $this->requestingOrganisationTransactionReference = $requestingOrganisationTransactionReference;
    }

    public function getOriginalTransactionReference(): ?string
    {
        return $this->originalTransactionReference;
    }

    public function setOriginalTransactionReference(?string $originalTransactionReference): void
    {
        $this->originalTransactionReference = $originalTransactionReference;
    }

    public function getCallbackUrl(): ?string
    {
        return $this->callbackUrl;
    }

    public function setCallbackUrl(?string $callbackUrl): void
    {
        $this->callbackUrl = $callbackUrl;
    }

    public function getCallbackData(): ?array
    {
        return $this->callbackData;
    }

    public function setCallbackData(?array $callbackData): void
    {
        $this->callbackData = $callbackData;
    }

    /**
     * @throws MVolaValidationException
     */
    private function validate(): void
    {
        $errors = [];

        if (empty($this->amount) || !is_numeric($this->amount)) {
            $errors[] = "Invalid amount";
        }
        if (empty($this->currency)) {
            $errors[] = "Currency is required";
        }
        if (empty($this->descriptionText)) {
            $errors[] = "Description is required";
        }
        if (empty($this->requestingOrganisationTransactionReference)) {
            $errors[] = "Requesting organisation transaction reference is required";
        }
        if (empty($this->debitParty) || empty($this->creditParty)) {
            $errors[] = "Both debit and credit parties are required";
        }
        if (!empty($errors)) {
            throw new MVolaValidationException("Validation failed: " . implode(", ", $errors));
        }
    }
}
