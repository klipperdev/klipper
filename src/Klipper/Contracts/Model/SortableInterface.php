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
 * Interface of sortable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface SortableInterface
{
    /**
     * Set the position.
     *
     * @param null|int $position The position
     *
     * @return static
     */
    public function setPosition(?int $position);

    /**
     * Get the position.
     */
    public function getPosition(): ?int;
}
