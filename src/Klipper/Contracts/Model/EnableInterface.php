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
 * Interface of enable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface EnableInterface
{
    /**
     * Set if the model is enabled.
     *
     * @param bool $enabled The enabled value
     *
     * @return static
     */
    public function setEnabled(bool $enabled);

    /**
     * Check if the model is enabled.
     */
    public function isEnabled(): bool;
}
