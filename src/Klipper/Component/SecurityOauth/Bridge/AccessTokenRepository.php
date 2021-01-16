<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Bridge;

use Klipper\Component\SecurityOauth\Authentication\AuthenticationManagerInterface;
use Klipper\Component\SecurityOauth\Authentication\Token\OauthToken;
use Klipper\Component\SecurityOauth\Repository\OauthAccessTokenRepositoryInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    private OauthAccessTokenRepositoryInterface $repository;

    private AuthenticationManagerInterface $authManager;

    public function __construct(
        OauthAccessTokenRepositoryInterface $repository,
        AuthenticationManagerInterface $authManager
    ) {
        $this->repository = $repository;
        $this->authManager = $authManager;
    }

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): AccessTokenEntityInterface
    {
        $accessToken = new AccessToken($userIdentifier, $scopes);
        $accessToken->setClient($clientEntity);

        return $accessToken;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        $this->authManager->authenticate(new OauthToken(
            $accessTokenEntity->getIdentifier(),
            $accessTokenEntity->getUserIdentifier(),
            $this->authManager->getFirewallName(),
            [],
            array_map(static function (ScopeEntityInterface $scope) {
                return $scope->getIdentifier();
            }, $accessTokenEntity->getScopes())
        ));

        $this->repository->createAccessToken(
            $accessTokenEntity->getIdentifier(),
            $accessTokenEntity->getUserIdentifier(),
            $accessTokenEntity->getClient()->getIdentifier(),
            $this->scopesToArray($accessTokenEntity->getScopes()),
            \DateTime::createFromImmutable($accessTokenEntity->getExpiryDateTime())
        );
    }

    public function revokeAccessToken($tokenId): void
    {
        $this->repository->revokeAccessToken($tokenId);
    }

    public function isAccessTokenRevoked($tokenId): bool
    {
        return null === $this->repository->findOneBy([
            'token' => $tokenId,
        ]);
    }

    private function scopesToArray(array $scopes): array
    {
        return array_map(function ($scope) {
            return $scope->getIdentifier();
        }, $scopes);
    }
}
