<?php

declare(strict_types=1);

namespace App\OAuth\Service;

use App\Entity\User;
use App\Http\HttpClientInterface;
use App\Repository\UserRepository;

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
