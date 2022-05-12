<?php

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepository
{
    public function findOneByIdOrFail(string $id): ?User;

    public function save(User $user): void;
}
