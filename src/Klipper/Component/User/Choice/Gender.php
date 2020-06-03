<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\User\Choice;

use Klipper\Component\Choice\PlaceholderableChoiceInterface;

/**
 * Gender.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
final class Gender implements PlaceholderableChoiceInterface
{
    public static function listIdentifiers(): array
    {
        return [
            'm' => 'gender.male',
            'f' => 'gender.female',
            'o' => 'gender.other',
        ];
    }

    public static function getValues(): array
    {
        return array_keys(static::listIdentifiers());
    }

    public static function getTranslationDomain(): string
    {
        return 'choices';
    }

    /**
     * Get the placeholder.
     */
    public static function getPlaceholder(): string
    {
        return 'gender.placeholder';
    }
}
