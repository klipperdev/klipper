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

use Klipper\Component\Portal\Model\Traits\PortalableInterface;
use Klipper\Contracts\Model\IdInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Portal user interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PortalUserInterface extends IdInterface, PortalableInterface
{
    public function __toString(): string;

    /**
     * @return static
     */
    public function setUser(?UserInterface $user);

    public function getUser(): ?UserInterface;
}
