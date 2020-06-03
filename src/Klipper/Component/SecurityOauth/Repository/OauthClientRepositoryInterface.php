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
use Klipper\Component\SecurityOauth\Model\OauthClientInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface OauthClientRepositoryInterface extends ObjectRepository
{
    /**
     * @param int|string $identifier
     */
    public function findEnabled($identifier, ?string $locale = null): ?OauthClientInterface;
}
