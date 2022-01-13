<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 */
class UserController
{
    public function __construct(private UserRepository $userRepository)
    {

    }

    /**
     * @Route("/{id}", name="users_get", methods={"GET"})
     */
    public function getUser(string $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse([
            'id' => $user->id(),
            'email' => $user->email(),
        ]);
    }

    /**
     * @Route("/register", name="users_register", methods={"GET"})
     */
    public function register(): JsonResponse
    {
        $user = new User('testid', 'moein@codenip.tech');
        $this->userRepository->save($user);
        return new JsonResponse(['success' => true]);
    }
}