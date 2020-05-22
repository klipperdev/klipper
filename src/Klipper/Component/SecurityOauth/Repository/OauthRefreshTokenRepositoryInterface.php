<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Klipper\Component\SecurityOauth\Model\OauthRefreshTokenInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface OauthRefreshTokenRepositoryInterface extends ObjectRepository
{
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createRefreshToken(string $refreshToken, string $accessToken, \DateTimeInterface $expiresAt): OauthRefreshTokenInterface;

    public function revokeRefreshToken(string $tokenId): void;
}
