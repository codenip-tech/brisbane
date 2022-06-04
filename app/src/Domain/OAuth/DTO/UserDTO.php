<?php

declare(strict_types=1);

namespace App\Domain\OAuth\DTO;

class UserDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $email
    ) {
    }

    public static function create(string $id, string $email): self
    {
        return new self($id, $email);
    }
}
