<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FreeCurrencyApiService
{
    public const BASE_CURRENCY_CODE = 'BYN';
    protected Client $client;
    protected const CURRENCY_API_URL = 'https://freecurrencyapi.net/api/v3/';

    public function __construct()
    {
        $headers = [
            'headers' => [
                'apikey' => $_ENV['CURRENCY_API_KEY']
            ]
        ];

        $this->client = new Client($headers);
    }

    /**
     * @throws GuzzleException
     */
    public function sendRequest(string $endpoint, array $params = []): array
    {
        $url = self::CURRENCY_API_URL . $endpoint;

        if (!isset($params['query']['base_currency'])) {
            $params['query']['base_currency'] = self::BASE_CURRENCY_CODE;
        }

        $response = $this->client->request('GET', $url, $params);

        return json_decode($response->getBody()->getContents(), true);
    }
}
