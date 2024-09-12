<?php

namespace DahRomy\MVola\Service\ApiClient;

interface MVolaApiClientInterface
{
    public function sendRequest(string $method, string $endpoint, array $data = []): array;
    public function setAccessToken(string $accessToken): void;
}
