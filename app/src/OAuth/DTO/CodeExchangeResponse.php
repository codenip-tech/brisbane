<?php

namespace App\OAuth\DTO;

class CodeExchangeResponse
{
    public function __construct(
        public readonly string $tokenType,
        public readonly int $expiresIn,
        public readonly string $accessToken,
        public readonly string $refreshToken,
    ) {
    }
}
