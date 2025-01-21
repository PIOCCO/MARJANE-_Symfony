<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AppCustomAuthenticator extends AbstractAuthenticator
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        // Checks if the current request is a POST to the /login route
        return $request->getPathInfo() == '/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        // Get the username and password from the request
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        // Check if credentials are correct
        if ('root' !== $username || '0000' !== $password) {
            throw new BadCredentialsException('Invalid credentials');
        }

        // Create a UserBadge with the username
        return new SelfValidatingPassport(new UserBadge($username));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Redirect to the customer index after successful login
        return new RedirectResponse($this->urlGenerator->generate('customer_index'));
    }

    public function onAuthenticationFailure(Request $request, \Symfony\Component\Security\Core\Exception\AuthenticationException $exception): ?Response
    {
        // Return a response with a failure message if authentication fails
        return new Response('Authentication failed: ' . $exception->getMessageKey(), Response::HTTP_FORBIDDEN);
    }
}
