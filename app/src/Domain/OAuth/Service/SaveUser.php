<?php

declare(strict_types=1);

namespace App\Domain\OAuth\Service;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;

class SaveUser
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function __invoke(string $id, string $email): User
    {
        if (null !== $user = $this->userRepository->findOneById($id)) {
            $user->setEmail($email);
        } else {
            $user = new User($id, $email);
        }

        $this->userRepository->save($user);

        return $user;
    }
}
