<?php

declare(strict_types=1);

namespace App\Domain\OAuth\Service;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use App\Infrastructure\Http\HttpClientInterface;

class GetProfile
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly UserRepository $userRepository,
        private readonly string $oauthGetProfileUrl
    ) {
    }

    public function __invoke(string $token): User
    {
        $response = $this->httpClient->get($this->oauthGetProfileUrl, [
            'headers' => [
                'Authorization' => \sprintf('Bearer %s', $token),
            ],
        ]);

        $data = \json_decode($response->getBody()->getContents(), true);

        $user = new User($data['id'], $data['email']);

        $this->userRepository->save($user);

        return $user;
    }
}
