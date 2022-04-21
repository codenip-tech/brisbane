<?php

declare(strict_types=1);

namespace App\Controller;

use App\OAuth\DTO\OAuthCredentialsDTO;
use App\OAuth\Service\CodeExchanger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly OAuthCredentialsDTO $dto,
        private readonly CodeExchanger $codeExchanger,
    ) {
    }

    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig', [
            'oauthDecideUrl' => $this->dto->oauthDecideUrl,
            'oauthClientId' => $this->dto->oauthClientId,
            'oauthResponseType' => $this->dto->oauthResponseType,
            'oauthRedirectUri' => $this->dto->oauthRedirectUri,
            'oauthRegisterUrl' => $this->dto->oauthRegisterUrl,
        ]);
    }

    #[Route('/oauth-validate-code', name: 'oauth_validate_code', methods: ['GET'])]
    public function oauthVerifyCode(Request $request): Response
    {
        $code = $request->query->get('code');
        $codeExchangeResponse = $this->codeExchanger->exchange($code);

        return $this->render('dashboard/dashboard/index.html.twig', [
            'accessToken' => $codeExchangeResponse->accessToken,
        ]);
    }
}
