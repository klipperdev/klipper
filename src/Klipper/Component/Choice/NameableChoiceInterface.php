<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Choice;

/**
 * Nameable choice interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface NameableChoiceInterface extends ChoiceInterface
{
    /**
     * Get the unique name.
     *
     * @return string
     */
    public static function getName(): string;
}
