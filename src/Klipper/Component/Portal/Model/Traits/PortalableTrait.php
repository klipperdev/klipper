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
 * Trait to indicate that the model is linked with an portal.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait PortalableTrait
{
    protected ?PortalInterface $portal = null;

    public function setPortal(?PortalInterface $portal): self
    {
        $this->portal = $portal;

        return $this;
    }

    public function getPortal(): ?PortalInterface
    {
        return $this->portal;
    }

    public function getPortalId()
    {
        return null !== $this->getPortal()
            ? $this->getPortal()->getId()
            : null;
    }

    public static function getPortalAssociationName(): string
    {
        return 'portal';
    }
}
