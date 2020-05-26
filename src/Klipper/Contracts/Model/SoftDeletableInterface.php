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
 * Interface of soft deletable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface SoftDeletableInterface
{
    /**
     * Set deleted at.
     *
     * @return static
     */
    public function setDeletedAt(?\DateTimeInterface $deletedAt = null);

    /**
     * Get deleted at.
     */
    public function getDeletedAt(): ?\DateTimeInterface;

    /**
     * Check if the resource is deleted.
     */
    public function isDeleted(): bool;
}
