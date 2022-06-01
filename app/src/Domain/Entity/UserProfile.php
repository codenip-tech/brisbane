<?php

namespace App\Domain\Entity;

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
        return new static($user->id(), '', '');
    }

    public function id(): string
    {
        return $this->id;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }
}
