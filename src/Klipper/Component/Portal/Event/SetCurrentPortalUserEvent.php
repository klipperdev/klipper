<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Event;

use Klipper\Component\Portal\Model\PortalUserInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * The event of set current portal user by the portal context.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SetCurrentPortalUserEvent extends Event
{
    protected ?PortalUserInterface $portalUser;

    public function __construct(?PortalUserInterface $portalUser)
    {
        $this->portalUser = $portalUser;
    }

    public function getPortalUser(): ?PortalUserInterface
    {
        return $this->portalUser;
    }
}
