<?php

namespace App\Entity;

class UserProfile
{
    private function __construct(
        private readonly string $id,
        private string $firstName,
        private string $lastName,
    ) {
    }

    public static function createEmpty(User $user)
    {
        return new static(
            $user->id(),
            '',
            ''
        );
    }
}
