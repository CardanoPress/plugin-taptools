<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\TapTools;

use CardanoPress\Dependencies\GuzzleHttp\Client as BaseClient;
use CardanoPress\Dependencies\GuzzleHttp\Exception\ConnectException;
use CardanoPress\Dependencies\GuzzleHttp\Exception\GuzzleException;
use CardanoPress\Dependencies\GuzzleHttp\Exception\RequestException;
use CardanoPress\Dependencies\GuzzleHttp\HandlerStack;
use CardanoPress\Dependencies\GuzzleHttp\Middleware;
use CardanoPress\Dependencies\GuzzleHttp\Psr7\Request;
use CardanoPress\Dependencies\GuzzleHttp\Psr7\Response;
use Closure;
use JsonException;

class Client
{
    private BaseClient $client;

    public const DELAY = 1000;
    public const MAX_RETRIES = 5;
    public const ENDPOINT = 'https://openapi.taptools.io/api/v1/';

    /**
     * Create a new Client instance
     */
    public function __construct(string $api_key, HandlerStack $handler = null)
    {
        $this->client = new BaseClient([
            'base_uri' => self::ENDPOINT,
            'timeout' => 10,
            'connect_timeout' => 10,
            'handler' => null === $handler ? $this->createHandlerStack() : $handler,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'x-api-key' => $api_key,
            ],
        ]);
    }

    private function createHandlerStack(): HandlerStack
    {
        $stack = HandlerStack::create();
        $stack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));

        return $stack;
    }

    private function retryDecider(): Closure
    {
        return static function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) {
            if ($retries >= self::MAX_RETRIES) {
                return false;
            }

            // @phpstan-ignore-next-line
            if ($exception instanceof ConnectException) {
                return true;
            }

            if ($response && $response->getStatusCode() >= 500) {
                return true;
            }

            return false;
        };
    }

    private function retryDelay(): Closure
    {
        return static function ($numberOfRetries) {
            return self::DELAY * $numberOfRetries;
        };
    }

    /**
     * Make a request to the API endpoint
     *
     * @param  string  $endpoint
     * @param  array   $query
     *
     * @return array
     */
    public function request(string $method, string $endpoint, array $options = []): array
    {
        $value = [
            'status_code' => 500,
            'data' => [],
        ];

        try {
            $response = $this->client->request($method, $endpoint, $options);

            $value['status_code'] = $response->getStatusCode();
            $value['data'] = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

            if (isset($value['data']['status_code'], $value['data']['error'], $value['data']['message'])) {
                $value['status_code'] = $value['data']['status_code'];

                $value['error'] = $value['data'];
                $value['data']  = [];
            }
        } catch (RequestException $error) {
            $response = $error->getResponse();

            try {
                $value['status_code'] = $response->getStatusCode();
                $value['error'] = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                $value['error'] = $e->getMessage();
            } finally {
                if (empty($value['error'])) {
                    $value['error'] = $error->getMessage();
                }
            }
        } catch (GuzzleException $e) {
            $value['error'] = $e->getMessage();
        } catch (JsonException $e) {
            $value['error'] = $e->getMessage();
        } finally {
            return $value;
        }
    }
}
