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
 * Interface of simple nameable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface SimpleNameableInterface
{
    /**
     * Set the name.
     *
     * @param null|string $name The name
     *
     * @return static
     */
    public function setName(?string $name);

    /**
     * Get the name.
     */
    public function getName(): ?string;
}
