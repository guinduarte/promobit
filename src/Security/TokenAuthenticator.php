<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em, $tokenGenerator;

    public function __construct(EntityManagerInterface $em, TokenGenerator $tokenGenerator)
    {
        $this->em = $em;

        $this->tokenGenerator = $tokenGenerator;
    }

    public function supports(Request $request): bool
    {
        return (bool) (!in_array($request->attributes->get('_route'), ['auth', 'requestPassword', 'resetPassword']));
    }

    public function getCredentials(Request $request)
    {
        return $request->headers->get('Authorization') ?? '';
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if (null === $credentials) {
            return null;
        }

        $payload = $this->tokenGenerator->decode($credentials);

        if ($payload) {
            $tokenId = $payload->acb->_id;

            // TODO: check if exist tokenId in database
            // $token = $this->tokenService->get($tokenId); // recover info from token in mongodb
            $identifier = $payload->acb->email; // $token->email; // get email from token

            return $userProvider->loadUserByIdentifier($identifier);
        }

        return false;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        # TODO: translate
        return new JsonResponse([
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new JsonResponse([
            'message' => 'Authentication Required'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}