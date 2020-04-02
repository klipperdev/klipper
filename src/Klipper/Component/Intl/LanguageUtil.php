<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Intl;

use Symfony\Component\Intl\Languages;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class LanguageUtil
{
    /**
     * Get the languages.
     *
     * @return array
     */
    public static function getLanguages(): array
    {
        return array_keys(Languages::getNames());
    }
}
