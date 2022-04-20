<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProfileController extends AbstractController
{
    #[Route('/me', name: 'oauth_api_me', methods: ['GET'])]
    public function me(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }
}