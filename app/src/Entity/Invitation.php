<?php

namespace App\Entity;

class Invitation
{
    private function __construct(
        private readonly string $id,
        private readonly string $token,
        private readonly Organization $organization,
        private readonly string $email,
    ) {
    }

    public static function create(string $id, string $token, Organization $organization, string $email): static
    {
        return new static($id, $token, $organization, $email);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function organization(): Organization
    {
        return $this->organization;
    }

    public function email(): string
    {
        return $this->email;
    }
}
