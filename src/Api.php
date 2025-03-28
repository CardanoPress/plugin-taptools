<?php

/**
 * @package ThemePlate
 */

namespace PBWebDev\CardanoPress\TapTools;

use CardanoPress\Dependencies\Psr\Log\LoggerInterface;
use CardanoPress\Traits\Loggable;

class Api
{
    use Loggable;

    protected Client $client;
    protected array $lastResponse = [];

    public function __construct(string $apiKey, LoggerInterface $logger = null)
    {
        $this->client = new Client($apiKey);

        if (null !== $logger) {
            $this->setLogger($logger);
        }
    }

    public function get(string $endpoint, array $query = []): array
    {
        return $this->request('GET', $endpoint, compact('query'));
    }

    public function post(string $endpoint, string $body = ''): array
    {
        return $this->request('POST', $endpoint, compact('body'));
    }

    public function request(string $method, string $endpoint, array $options = []): array
    {
        $response = $this->client->request($method, $endpoint, $options);
        $this->lastResponse = $response;

        if (200 !== $response['status_code'] || ! empty($response['error'])) {
            $this->log($endpoint);
            $this->log(print_r($options, true));
            $this->log(print_r($response, true));
        }

        return $response;
    }

    public function getResponse(string $key = null)
    {
        if (null === $key) {
            return $this->lastResponse;
        }

        return $this->lastResponse[$key] ?? null;
    }

    public function getAvailableQuoteCurrencies(): array
    {
        $response = $this->get('token/quote/available');

        return 200 === $response['status_code'] ? $response['data'] : [];
    }

    public function getTopLiquidityTokens(int $perPage = 10, int $page = 1): array
    {
        $response = $this->get('token/top/liquidity', compact('perPage', 'page'));

        return 200 === $response['status_code'] ? $response['data'] : [];
    }

    public function getTopMarketCapTokens(int $perPage = 10, int $page = 1): array
    {
        $response = $this->get('token/top/mcap', compact('perPage', 'page'));

        return 200 === $response['status_code'] ? $response['data'] : [];
    }

    public function getTokenPrices(array $units): array
    {
        $response = $this->post('token/prices', json_encode($units));

        return 200 === $response['status_code'] ? $response['data'] : [];
    }

    public function getTokenById(string $id): array
    {
        $response = $this->get('integration/asset', compact('id'));

        return 200 === $response['status_code'] ? $response['data'] : [];
    }
}
