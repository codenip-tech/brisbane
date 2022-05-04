<?php

declare(strict_types=1);

namespace App\Http;

use App\Exception\HttpClientException;
use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    /**
     * @throws HttpClientException
     */
    public function get(string $uri, array $options = []): ResponseInterface;

    /**
     * @throws HttpClientException
     */
    public function post(string $uri, array $options = []): ResponseInterface;
}
