<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice\Util;

use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ChoiceUtil
{
    public static function getUniqueId(ChoiceInterface $choice): string
    {
        return $choice->getType().'|'.$choice->getValue();
    }

    /**
     * @param ChoiceInterface[] $choices
     *
     * @return ChoiceInterface[] The map of choices
     */
    public static function getMapChoices(array $choices): array
    {
        $values = [];

        foreach ($choices as $choice) {
            $values[static::getUniqueId($choice)] = $choice;
        }

        return $values;
    }

    /**
     * @param ChoiceInterface[] $choices
     */
    public static function getChoice(array $choices, string $type, ?string $value): ?ChoiceInterface
    {
        return $choices[$type.'|'.$value] ?? null;
    }
}
