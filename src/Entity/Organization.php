<?php

namespace App\Entity;

use JetBrains\PhpStorm\Pure;

class Organization
{
    /**
     * @var User[]
     */
    private array $users = [];

    private function __construct(
        private readonly string $id,
        private string $name,
    ) {}

    public static function create(string $id, string $name): static {
        return new static($id, $name);
    }

    public function id(): string {
        return $this->id;
    }

    public function users(): array
    {
        return $this->users;
    }

    public function name(): string
    {
        return $this->name;
    }


}