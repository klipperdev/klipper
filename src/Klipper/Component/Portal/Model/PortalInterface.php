<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Model;

use Klipper\Contracts\Model\IdInterface;

/**
 * Portal interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PortalInterface extends IdInterface
{
    public function __toString(): string;

    /**
     * @return static
     */
    public function setPortalName(?string $name);

    public function getPortalName(): ?string;

    /**
     * @return static
     */
    public function setPortalEnabled(bool $enabled);

    public function isPortalEnabled(): bool;
}
