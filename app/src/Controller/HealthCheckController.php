<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController
{
    #[Route('/', name: 'health_check', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new JsonResponse(['status' => 'ok']);
    }
}
