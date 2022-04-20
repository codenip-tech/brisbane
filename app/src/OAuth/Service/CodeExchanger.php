<?php

namespace App\OAuth\Service;

use App\OAuth\DTO\CodeExchangeResponse;
use App\OAuth\DTO\OAuthCredentialsDTO;
use GuzzleHttp\Client;

class CodeExchanger
{
    private readonly Client $httpClient;

    public function __construct(
        private readonly string $oauthTokenUrl,
        private readonly OAuthCredentialsDTO $oAuthCredentialsDTO,
    ) {
        $this->httpClient = new Client();
    }

    public function exchange(string $code): CodeExchangeResponse
    {
        $response = $this->httpClient->post($this->oauthTokenUrl, [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->oAuthCredentialsDTO->oauthRedirectUri,
                'code' => $code,
                'client_id' => $this->oAuthCredentialsDTO->oauthClientId,
                'client_secret' => $this->oAuthCredentialsDTO->oauthSecret
            ]
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