<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Surface\Choice;

use Klipper\Component\Choice\ChoiceInterface;

/**
 * Surface unit choice.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
final class SurfaceUnit implements ChoiceInterface
{
    public static function listIdentifiers(): array
    {
        return [
            'metric' => 'surface_unit.metric',
            'imperial' => 'surface_unit.imperial',
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
}
