<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HealthCheckControllerTest extends WebTestCase
{
    private const ENDPOINT = '/';

    public function testHealthCheck(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, self::ENDPOINT);

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertArrayHasKey('status', $responseData);
        self::assertEquals('ok', $responseData['status']);
    }
}