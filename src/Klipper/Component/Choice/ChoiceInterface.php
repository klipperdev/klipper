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
 * Choice interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ChoiceInterface
{
    /**
     * Get the list of choice.
     */
    public static function listIdentifiers(): array;

    /**
     * Get all values.
     *
     * @return string[]
     */
    public static function getValues(): array;

    /**
     * Get the translation domain name.
     */
    public static function getTranslationDomain(): string;
}
