<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\User\Model\Traits;

use Klipper\Component\User\Model\ProfileInterface;

/**
 * Profile interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ProfileableInterface
{
    /**
     * @return static
     */
    public function setProfile(?ProfileInterface $profile);

    public function getProfile(): ?ProfileInterface;

    public function getFullName($format = '{firstName} {lastName}'): string;
}
