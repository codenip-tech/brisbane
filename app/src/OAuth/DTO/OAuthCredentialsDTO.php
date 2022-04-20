<?php

declare(strict_types=1);

namespace App\OAuth\DTO;

class OAuthCredentialsDTO
{
    public function __construct(
        public readonly string $oauthSecret,
        public readonly string $oauthDecideUrl,
        public readonly string $oauthClientId,
        public readonly string $oauthResponseType,
        public readonly string $oauthRedirectUri,
        public readonly string $oauthRegisterUrl
    )
    {
    }
}