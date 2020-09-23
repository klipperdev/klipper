<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal;

use Klipper\Component\Portal\Model\PortalInterface;
use Klipper\Component\Portal\Model\PortalUserInterface;

/**
 * Portal Context interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PortalContextInterface
{
    /**
     * Set the current used portal.
     */
    public function setCurrentPortal(?PortalInterface $portal): void;

    /**
     * Get the current used portal.
     */
    public function getCurrentPortal(): ?PortalInterface;

    /**
     * Set the current used portal user.
     *
     * @param null|PortalUserInterface $portalUser The current portal user
     */
    public function setCurrentPortalUser(?PortalUserInterface $portalUser): void;

    /**
     * Get the current used portal user.
     */
    public function getCurrentPortalUser(): ?PortalUserInterface;
}
