<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Authentication\Provider;

use Klipper\Component\SecurityOauth\Authentication\Token\OauthToken;
use Klipper\Component\SecurityOauth\Authentication\Token\RequestOauthToken;
use Klipper\Component\SecurityOauth\Exception\RuntimeException;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthProvider implements AuthenticationProviderInterface
{
    private UserProviderInterface $userProvider;

    private ResourceServer $resourceServer;

    private string $providerKey;

    public function __construct(
        UserProviderInterface $userProvider,
        ResourceServer $resourceServer,
        string $providerKey
    ) {
        $this->userProvider = $userProvider;
        $this->resourceServer = $resourceServer;
        $this->providerKey = $providerKey;
    }

    /**
     * @throws OAuthServerException By ResourceServer::validateAuthenticatedRequest()
     */
    public function authenticate(TokenInterface $token): TokenInterface
    {
        if (!$this->supports($token)) {
            throw new RuntimeException(sprintf(
                'This authentication provider can only handle tokes of type "%s"',
                OauthToken::class
            ));
        }

        if ($token instanceof RequestOauthToken) {
            $request = $this->resourceServer->validateAuthenticatedRequest($token->getServerRequest());
            $accessToken = $request->getAttribute('oauth_access_token_id');
            $user = $this->getAuthenticatedUser($request->getAttribute('oauth_user_id'));
        } else {
            /** @var OauthToken $token */
            $accessToken = $token->getToken();
            $user = $this->getAuthenticatedUser($token->getUsername());
        }

        return new OauthToken(
            $accessToken,
            $user,
            $this->providerKey,
            $user->getRoles()
        );
    }

    public function supports(TokenInterface $token): bool
    {
        return $token instanceof OauthToken && $this->providerKey === $token->getProviderKey();
    }

    private function getAuthenticatedUser(string $userIdentifier): ?UserInterface
    {
        return empty($userIdentifier)
            ? null
            : $this->userProvider->loadUserByUsername($userIdentifier);
    }
}
