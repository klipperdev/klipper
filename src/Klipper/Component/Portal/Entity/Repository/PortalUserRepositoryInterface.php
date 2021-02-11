<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Entity\Repository;

use Doctrine\Persistence\ObjectRepository;
use Klipper\Component\Portal\Model\PortalUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Portal User repository class.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PortalUserRepositoryInterface extends ObjectRepository
{
    /**
     * @param string             $portalName The portal name
     * @param null|UserInterface $user       The user
     */
    public function findCurrentPortalUserByPortalName(string $portalName, ?UserInterface $user): ?PortalUserInterface;

    /**
     * @param int|string $id The user id
     */
    public function findPortalUserById($id): ?PortalUserInterface;
}
