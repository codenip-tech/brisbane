<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Domain\OAuth\DTO\OAuthCredentialsDTO;
use App\Domain\OAuth\Service\CodeExchanger;
use App\Domain\OAuth\Service\GetProfile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly OAuthCredentialsDTO $dto,
        private readonly CodeExchanger $codeExchanger,
        private readonly GetProfile $getProfile
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

        $user = $this->getProfile->__invoke($codeExchangeResponse->accessToken);

        return $this->render('dashboard/dashboard/index.html.twig', [
            'id' => $user->id(),
            'email' => $user->email(),
        ]);
    }
}
