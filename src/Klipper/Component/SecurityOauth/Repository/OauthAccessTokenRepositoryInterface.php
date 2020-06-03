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

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectRepository;
use Klipper\Component\SecurityOauth\Model\OauthAccessTokenInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface OauthAccessTokenRepositoryInterface extends ObjectRepository
{
    /**
     * @param string[] $scopes
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createAccessToken(string $accessToken, string $username, string $clientToken, array $scopes, \DateTimeInterface $expiresAt): OauthAccessTokenInterface;

    public function revokeAccessToken(string $tokenId): void;
}
