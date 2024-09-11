<?php

namespace DahRomy\Mvola\Factory;

use DahRomy\Mvola\Service\ApiClient\MVolaApiClient;
use GuzzleHttp\Client;

class MVolaApiClientFactory
{
    public function create(string $environment, string $merchantNumber, string $companyName): MVolaApiClient
    {
        $baseUrl = $environment === 'sandbox' ? 'https://devapi.mvola.mg' : 'https://api.mvola.mg';

        return new MVolaApiClient(
            new Client(),
            $baseUrl,
            $merchantNumber,
            $companyName
        );
    }
}