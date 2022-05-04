<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepository
{
    public function findOneByIdOrFail(string $id): ?User;

    public function save(User $user): void;
}
