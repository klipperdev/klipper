<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Choice\Util;

/**
 * Choice Utils.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class ChoiceUtil
{
    /**
     * Get all values of the grouped choice identifiers keys.
     *
     * @param array $choiceIdentifiers The choice identifiers
     *
     * @return string[]
     */
    public static function getValues(array $choiceIdentifiers): array
    {
        $choices = [];

        foreach ($choiceIdentifiers as $key => $value) {
            $choices[] = \is_array($value) ? self::getValues($value) : [$key];
        }

        return \count($choices) > 0 ? array_merge(...$choices) : $choices;
    }
}
