<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Contracts\Model;

use Klipper\Component\Security\Model\UserInterface;

/**
 * Interface of User Trackable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface UserTrackableInterface
{
    /**
     * Get the user that created the record.
     */
    public function getCreatedBy(): ?UserInterface;

    /**
     * Get the user id that created the record.
     *
     * @return null|int|string
     */
    public function getCreatedById();

    /**
     * Get the user that updated the record.
     */
    public function getUpdatedBy(): ?UserInterface;

    /**
     * Get the user id that updated the record.
     *
     * @return null|int|string
     */
    public function getUpdatedById();
}
