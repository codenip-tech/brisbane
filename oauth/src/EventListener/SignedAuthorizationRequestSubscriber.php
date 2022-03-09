<?php

//src/EventListener/SignedAuthorizationRequestSubscriber.php

namespace App\EventListener;

use League\Bundle\OAuth2ServerBundle\Event\AuthorizationRequestResolveEvent;
use League\Bundle\OAuth2ServerBundle\OAuth2Events;
use League\Bundle\OAuth2ServerBundle\OAuth2Grants;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SignedAuthorizationRequestSubscriber implements EventSubscriberInterface
{
    public const ATTRIBUTE_DECISION = 'decision';
    public const ATTRIBUTE_DECISION_ALLOW = 'allow';

    /**
     * @var UriSigner
     */
    private $uriSigner;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var string
     */
    private $decisionRoute;

    public function __construct(UriSigner $uriSigner, RequestStack $requestStack, UrlGeneratorInterface $urlGenerator, string $decisionRoute = 'oauth2_authorize')
    {
        $this->uriSigner = $uriSigner;
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
        $this->decisionRoute = $decisionRoute;
    }

    public function processSignedAuthorizationRequest(AuthorizationRequestResolveEvent $event): void
    {
        if (null === $request = $this->requestStack->getMainRequest()) {
            return;
        }

        $currentUri = $request->getRequestUri();

        if (!$this->uriSigner->check($currentUri)) {
            return;
        }

        if (!$this->canResolveAuthorizationRequest($event, $request)) {
            return;
        }

        $event->resolveAuthorization($this->isAuthorizationAllowed($request));
    }

    private function canResolveAuthorizationRequest(AuthorizationRequestResolveEvent $event, Request $request): bool
    {
        if (!$request->query->has(self::ATTRIBUTE_DECISION)) {
            return false;
        }

        if ($request->query->get('client_id') !== $event->getClient()->getIdentifier()) {
            return false;
        }

        if ($request->query->get('response_type') !== $this->getResponseType($event)) {
            return false;
        }

        if ($request->query->get('redirect_uri') !== $event->getRedirectUri()) {
            return false;
        }

        if ($request->query->get('scope') !== $this->getScope($event)) {
            return false;
        }

        return true;
    }

    private function getResponseType(AuthorizationRequestResolveEvent $event): string
    {
        return match ($event->getGrantTypeId()) {
            OAuth2Grants::AUTHORIZATION_CODE => 'code',
            OAuth2Grants::IMPLICIT => 'token',
            default => $event->getGrantTypeId(),
        };
    }

    private function getScope(AuthorizationRequestResolveEvent $event): ?string
    {
        $scopes = $event->getScopes();

        if (empty($scopes)) {
            return null;
        }

        return implode(' ', array_map('strval', $scopes));
    }

    private function isAuthorizationAllowed(Request $request): bool
    {
        return self::ATTRIBUTE_DECISION_ALLOW === $request->get(self::ATTRIBUTE_DECISION);
    }

    public function redirectToDecisionRoute(AuthorizationRequestResolveEvent $event): void
    {
        $params = [
            'client_id' => $event->getClient()->getIdentifier(),
            'response_type' => $this->getResponseType($event),
        ];

        if (null !== $redirectUri = $event->getRedirectUri()) {
            $params['redirect_uri'] = $redirectUri;
        }

        if (null !== $state = $event->getState()) {
            $params['state'] = $state;
        }

        $scope = $this->getScope($event);
        if (null !== $scope) {
            $params['scope'] = $scope;
        }

        $event->setResponse(
            new RedirectResponse(
                $this->urlGenerator->generate($this->decisionRoute, $params)
            )
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OAuth2Events::AUTHORIZATION_REQUEST_RESOLVE => [
                ['processSignedAuthorizationRequest', 100],
                ['redirectToDecisionRoute', 50],
            ],
        ];
    }
}
