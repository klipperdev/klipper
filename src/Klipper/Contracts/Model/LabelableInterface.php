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
 * Interface of labelable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface LabelableInterface
{
    /**
     * Set the label.
     *
     * @param null|string $label The label
     *
     * @return static
     */
    public function setLabel(?string $label);

    /**
     * Get the label.
     */
    public function getLabel(): ?string;
}
