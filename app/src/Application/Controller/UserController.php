<?php

namespace App\Application\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UserController
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    #[Route('/{id}', name: 'users_get', methods: ['GET'])]
    public function getUser(string $id): JsonResponse
    {
        $user = $this->userRepository->findOneByIdOrFail($id);

        return new JsonResponse([
            'id' => $user->id(),
            'email' => $user->email(),
        ]);
    }
}
