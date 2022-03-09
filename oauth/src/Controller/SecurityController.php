<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\EventListener\SignedAuthorizationRequestSubscriber;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private UriSigner $uriSigner;
    private string $authorizationRoute;

    public function __construct(UriSigner $uriSigner, string $authorizationRoute = 'oauth2_authorize')
    {
        $this->uriSigner = $uriSigner;
        $this->authorizationRoute = $authorizationRoute;
    }

    #[Route('/register', name: 'oauth_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->getData()->getPassword()));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('oauth_login');
        }

        return $this->renderForm('user/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/login', name: 'oauth_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('target_path');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/authorize/decide", name="oauth2_decision")
     */
    public function decisionAction(Request $request): Response
    {
        return $this->render('user/decide.html.twig', [
            'allow_uri' => $this->buildDecidedUri($request, true),
            'deny_uri' => $this->buildDecidedUri($request, false),
        ]);
    }

    private function buildDecidedUri(Request $request, bool $allowed): string
    {
        $currentQuery = $request->query->all();
        $decidedQuery = array_merge($currentQuery, [SignedAuthorizationRequestSubscriber::ATTRIBUTE_DECISION => $this->buildDecisionValue($allowed)]);
        $decidedUri = $this->generateUrl($this->authorizationRoute, $decidedQuery);

        return $this->uriSigner->sign($decidedUri);
    }

    private function buildDecisionValue(bool $allowed): string
    {
        return $allowed ? SignedAuthorizationRequestSubscriber::ATTRIBUTE_DECISION_ALLOW : '';
    }
}
