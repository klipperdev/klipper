<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Authenticator;

use Klipper\Component\HttpFoundation\Psr7\Psr7WrappedRequest;
use Klipper\Component\HttpFoundation\Psr7\Psr7WrappedResponse;
use Klipper\Component\SecurityOauth\Authenticator\Passport\Badge\OauthScopesBadge;
use Klipper\Component\SecurityOauth\Authenticator\Passport\Credentials\OauthCredentials;
use Klipper\Component\SecurityOauth\Authenticator\Traits\OauthCreateTokenTrait;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Authenticator for oauth.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthAuthenticator extends AbstractAuthenticator implements AuthenticatorInterface, AuthenticationEntryPointInterface
{
    use OauthCreateTokenTrait;

    private UserProviderInterface $userProvider;

    private ResourceServer $resourceServer;

    public function __construct(
        UserProviderInterface $userProvider,
        ResourceServer $resourceServer
    ) {
        $this->userProvider = $userProvider;
        $this->resourceServer = $resourceServer;
    }

    public function supports(Request $request): bool
    {
        return 0 === strpos($request->headers->get('Authorization', ''), 'Bearer ');
    }

    /**
     * @throws OAuthServerException By ResourceServer::validateAuthenticatedRequest()
     */
    public function authenticate(Request $request): Passport
    {
        try {
            $oauthRequest = $this->resourceServer->validateAuthenticatedRequest(new Psr7WrappedRequest($request));

            return new Passport(
                new UserBadge($oauthRequest->getAttribute('oauth_user_id'), [$this->userProvider, 'loadUserByIdentifier']),
                new OauthCredentials($oauthRequest->getAttribute('oauth_access_token_id')),
                [new OauthScopesBadge($oauthRequest->getAttribute('oauth_scopes', []))]
            );
        } catch (\Throwable $e) {
            throw new AuthenticationException($e->getMessage(), 0, $e);
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * @throws
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $previousException = $exception->getPrevious();
        $response = null;

        if ($previousException instanceof OAuthServerException) {
            $psr7Response = $previousException->generateHttpResponse(new Psr7WrappedResponse(new Response()));
            $response = $psr7Response->getResponse();
        } elseif (null !== $previousException) {
            throw $previousException;
        }

        return $response;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $response = new Response();
        $response->headers->set('WWW-Authenticate', sprintf('Bearer realm="%s"', 'Oauth token required'));
        $response->setStatusCode(401);

        return $response;
    }
}
