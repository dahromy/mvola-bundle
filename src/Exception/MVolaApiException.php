<?php

namespace DahRomy\MVola\Exception;

class MVolaApiException extends \Exception
{
    private array $errorDetails;

    public function __construct(string $message, int $code = 0, \Throwable $previous = null, array $errorDetails = [])
    {
        parent::__construct($message, $code, $previous);
        $this->errorDetails = $errorDetails;
    }

    public function getErrorDetails(): array
    {
        return $this->errorDetails;
    }
}
