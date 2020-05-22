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

use Klipper\Component\SecurityOauth\Repository\OauthAuthCodeRepositoryInterface;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    private OauthAuthCodeRepositoryInterface $repository;

    public function __construct(OauthAuthCodeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getNewAuthCode(): AuthCodeEntityInterface
    {
        return new AuthCode();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $this->repository->createAuthCode(
            $authCodeEntity->getIdentifier(),
            $authCodeEntity->getUserIdentifier(),
            $authCodeEntity->getClient()->getIdentifier(),
            $authCodeEntity->getRedirectUri(),
            $this->scopesToArray($authCodeEntity->getScopes()),
            $authCodeEntity->getExpiryDateTime()
        );
    }

    public function revokeAuthCode($codeId): void
    {
        $this->repository->revokeAuthCode($codeId);
    }

    public function isAuthCodeRevoked($codeId): bool
    {
        return null === $this->repository->findOneBy([
                'token' => $codeId,
            ]);
    }

    private function scopesToArray(array $scopes): array
    {
        return array_map(function ($scope) {
            return $scope->getIdentifier();
        }, $scopes);
    }
}
