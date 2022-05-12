<?php

namespace App\Domain\OAuth\Service;

use App\Http\HttpClientInterface;
use App\Domain\OAuth\DTO\CodeExchangeResponse;
use App\Domain\OAuth\DTO\OAuthCredentialsDTO;

class CodeExchanger
{
    public function __construct(
        private readonly string $oauthTokenUrl,
        private readonly OAuthCredentialsDTO $oAuthCredentialsDTO,
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function exchange(string $code): CodeExchangeResponse
    {
        $response = $this->httpClient->post($this->oauthTokenUrl, [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->oAuthCredentialsDTO->oauthRedirectUri,
                'code' => $code,
                'client_id' => $this->oAuthCredentialsDTO->oauthClientId,
                'client_secret' => $this->oAuthCredentialsDTO->oauthSecret,
            ],
        ]);

        $parsedResponse = json_decode($response->getBody()->getContents(), true);

        return new CodeExchangeResponse(
            $parsedResponse['token_type'],
            $parsedResponse['expires_in'],
            $parsedResponse['access_token'],
            $parsedResponse['refresh_token'],
        );
    }
}
