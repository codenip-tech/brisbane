<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Infrastructure\Exception\HttpClientException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements HttpClientInterface
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function get(string $uri, array $options = []): ResponseInterface
    {
        try {
            return $this->client->get($uri, $options);
        } catch (ClientException|GuzzleException $e) {
            throw new HttpClientException($e->getResponse()->getStatusCode(), $e->getMessage());
        }
    }

    public function post(string $uri, array $options = []): ResponseInterface
    {
        try {
            return $this->client->post($uri, $options);
        } catch (ClientException|GuzzleException $e) {
            throw new HttpClientException($e->getResponse()->getStatusCode(), $e->getMessage());
        }
    }
}
