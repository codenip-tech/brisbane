<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepository
{
    public function find(string $id): ?User;

    public function save(User $user): void;
}
