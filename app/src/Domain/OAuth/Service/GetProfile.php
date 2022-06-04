<?php

declare(strict_types=1);

namespace App\Domain\OAuth\Service;

use App\Domain\OAuth\DTO\UserDTO;
use App\Infrastructure\Http\HttpClientInterface;

class GetProfile
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $oauthGetProfileUrl
    ) {
    }

    public function __invoke(string $token): UserDTO
    {
        $response = $this->httpClient->get($this->oauthGetProfileUrl, [
            'headers' => [
                'Authorization' => \sprintf('Bearer %s', $token),
            ],
        ]);

        $data = \json_decode($response->getBody()->getContents(), true);

        return UserDTO::create($data['id'], $data['email']);
    }
}
