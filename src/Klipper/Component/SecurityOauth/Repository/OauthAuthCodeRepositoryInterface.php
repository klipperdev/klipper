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
use Klipper\Component\SecurityOauth\Model\OauthAuthCodeInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface OauthAuthCodeRepositoryInterface extends ObjectRepository
{
    /**
     * @param string[] $scopes
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createAuthCode(string $authCode, string $username, string $clientToken, ?string $redirectUri, array $scopes, \DateTimeInterface $expiresAt): OauthAuthCodeInterface;

    public function revokeAuthCode(string $authCode): void;
}
