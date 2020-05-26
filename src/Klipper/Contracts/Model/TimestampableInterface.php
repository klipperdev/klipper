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

/**
 * Timestampable interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface TimestampableInterface
{
    /**
     * Set the date of update of model.
     *
     * @param null|\DateTimeInterface $createdAt The created datetime
     *
     * @return static
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt);

    /**
     * Get the date of create of model.
     */
    public function getCreatedAt(): ?\DateTimeInterface;

    /**
     * Set the date of update of model.
     *
     * @param null|\DateTimeInterface $updatedAt The updated datetime
     *
     * @return static
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt);

    /**
     * Get the date of update of model.
     */
    public function getUpdatedAt(): ?\DateTimeInterface;
}
