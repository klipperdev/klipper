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
 * Placeholderable choice interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PlaceholderableChoiceInterface extends ChoiceInterface
{
    /**
     * Get the placeholder.
     */
    public static function getPlaceholder(): string;
}
