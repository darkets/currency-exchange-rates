<?php
declare(strict_types=1);

namespace App\Api;

use App\Currency;
use App\Interfaces\ExchangeAPIInterface;
use App\Result;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FreeCurrencyAPI implements ExchangeAPIInterface
{
    private const BASE_URL = 'https://api.freecurrencyapi.com/v1/latest';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false,
        ]);
    }

    private function buildUrl(string $baseIsoCode, string $currencyIsoCode): string
    {
        return self::BASE_URL . '?' . http_build_query([
                'apikey' => $_ENV['FREECURRENCY_API_KEY'],
                'base_currency' => $baseIsoCode,
                'currencies' => $currencyIsoCode,
            ]);
    }

    public function fetchExchangeData(Currency $baseCurrency, Currency $currency): ?Result
    {
        $baseIsoCode = $baseCurrency->getIsoCode();
        $currencyIsoCode = $currency->getIsoCode();

        $url = $this->buildUrl($baseIsoCode, $currencyIsoCode);

        try {
            $response = $this->client->get($url);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception(
                    "API Request Failed! Base Currency: $baseIsoCode, Currency: $currencyIsoCode"
                );
            }

            $data = json_decode($response->getBody()->getContents());

            if (empty($data) || !property_exists($data, 'data') || !property_exists($data->data, $currencyIsoCode)) {
                return null;
            }

            $rate = $data->data->$currencyIsoCode;
            return new Result($currency, $rate, $this::BASE_URL);
        } catch (GuzzleException $e) {
            throw new \Exception('API Request Failed: ' . $e->getMessage());
        }
    }
}
