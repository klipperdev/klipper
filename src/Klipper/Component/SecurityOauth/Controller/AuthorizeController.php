<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Controller;

use Klipper\Component\HttpFoundation\Psr7\Psr7WrappedRequest;
use Klipper\Component\HttpFoundation\Psr7\Psr7WrappedResponse;
use Klipper\Component\SecurityOauth\Bridge\User;
use Klipper\Component\SecurityOauth\Exception\RuntimeException;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class AuthorizeController
{
    private AuthorizationServer $authServer;

    private TokenStorageInterface $tokenStorage;

    public function __construct(
        AuthorizationServer $authServer,
        TokenStorageInterface $tokenStorage
    ) {
        $this->authServer = $authServer;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(Request $request): Response
    {
        $psr7Request = new Psr7WrappedRequest($request);
        $psr7Response = new Psr7WrappedResponse(new Response());

        try {
            $token = $this->tokenStorage->getToken();

            if (null === $token || $token instanceof AnonymousToken) {
                throw new RuntimeException('The oauth authorize controller must have a authenticated user');
            }

            $authRequest = $this->authServer->validateAuthorizationRequest($psr7Request);

            $authRequest->setUser(new User($token->getUsername()));
            $authRequest->setAuthorizationApproved(true);

            $this->authServer->completeAuthorizationRequest($authRequest, $psr7Response);

            return $psr7Response->getResponse();
        } catch (OAuthServerException $exception) {
            $exception->generateHttpResponse($psr7Response);

            return $psr7Response->getResponse();
        }
    }
}
