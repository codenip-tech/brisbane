<?php

namespace App\Entity;

class User
{
    /**
     * @var Organization[]
     */
    private array $organizations = [];

    private function __construct(
        private readonly string $id,
        private string $email,
    ) {}

    public static function create(string $id, string $email): static
    {
        return new static($id, $email);
    }

    public function id(): string {
        return $this->id;
    }
}