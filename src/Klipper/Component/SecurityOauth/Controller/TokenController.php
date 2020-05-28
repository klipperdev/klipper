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
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TokenController
{
    private AuthorizationServer $authServer;

    public function __construct(AuthorizationServer $authServer)
    {
        $this->authServer = $authServer;
    }

    public function __invoke(Request $request): Response
    {
        $psr7Request = new Psr7WrappedRequest($request);
        $psr7Response = new Psr7WrappedResponse(new Response());

        try {
            $this->authServer->respondToAccessTokenRequest($psr7Request, $psr7Response);

            return $psr7Response->getResponse();
        } catch (OAuthServerException $exception) {
            $exception->generateHttpResponse($psr7Response);

            return $psr7Response->getResponse();
        }
    }
}
