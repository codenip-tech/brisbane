<?php

namespace App\Application\Security;

use App\Domain\OAuth\Service\CodeExchanger;
use App\Domain\OAuth\Service\GetProfile;
use App\Domain\OAuth\Service\SaveUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class OauthCodeAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private CodeExchanger $codeExchanger,
        private GetProfile $getProfile,
        private SaveUser $saveUser,
    ) {
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return $request->query->has('code');
    }

    public function authenticate(Request $request): Passport
    {
        $code = $request->query->get('code');
        if (null === $code) {
            throw new CustomUserMessageAuthenticationException('No oauth code provided');
        }

        $codeExchangeResponse = $this->codeExchanger->exchange($code);

        $userDTO = $this->getProfile->__invoke($codeExchangeResponse->accessToken);

        $user = $this->saveUser->__invoke($userDTO->id, $userDTO->email);

        return new SelfValidatingPassport(new UserBadge($user->email()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return new RedirectResponse('/admin');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
