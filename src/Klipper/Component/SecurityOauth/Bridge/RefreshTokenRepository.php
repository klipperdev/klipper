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

use Klipper\Component\SecurityOauth\Repository\OauthRefreshTokenRepositoryInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    private OauthRefreshTokenRepositoryInterface $repository;

    public function __construct(OauthRefreshTokenRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getNewRefreshToken(): RefreshTokenEntityInterface
    {
        return new RefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        $this->repository->createRefreshToken(
            $refreshTokenEntity->getIdentifier(),
            $refreshTokenEntity->getAccessToken()->getIdentifier(),
            $refreshTokenEntity->getExpiryDateTime()
        );
    }

    public function revokeRefreshToken($tokenId): void
    {
        $this->repository->revokeRefreshToken($tokenId);
    }

    public function isRefreshTokenRevoked($tokenId): bool
    {
        return null === $this->repository->findOneBy([
            'token' => $tokenId,
        ]);
    }
}
