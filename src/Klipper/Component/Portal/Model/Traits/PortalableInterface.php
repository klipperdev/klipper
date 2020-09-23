<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Model\Traits;

use Klipper\Component\Portal\Model\PortalInterface;

/**
 * Interface to indicate that the model is linked with an portal.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PortalableInterface
{
    /**
     * Set the portal.
     *
     * @param null|PortalInterface $portal The portal
     *
     * @return static
     */
    public function setPortal(?PortalInterface $portal);

    /**
     * Get the portal.
     */
    public function getPortal(): ?PortalInterface;

    /**
     * Get the portal id.
     *
     * @return null|int|string
     */
    public function getPortalId();
}
