<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityExtra\Model\Traits;

use Klipper\Component\Security\Model\Traits\OwnerableOptionalInterface as BaseOwnerableOptionalInterface;
use Klipper\Component\Security\Model\UserInterface;

/**
 * Interface of add dependency entity with an optional user.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface OwnerableOptionalInterface extends BaseOwnerableOptionalInterface
{
    /**
     * Set the owner.
     *
     * @param null|UserInterface $user The organization
     *
     * @return static
     */
    public function setOwner(?UserInterface $user);

    /**
     * Get the owner.
     */
    public function getOwner(): ?UserInterface;
}
