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

use Klipper\Component\Portal\Model\PortalInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * The event of set current portal by the portal context.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SetCurrentPortalEvent extends Event
{
    protected ?PortalInterface $portal;

    public function __construct(?PortalInterface $portal)
    {
        $this->portal = $portal;
    }

    public function getPortal(): ?PortalInterface
    {
        return $this->portal;
    }
}
