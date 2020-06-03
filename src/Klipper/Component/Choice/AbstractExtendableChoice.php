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
 * Extendable choice interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractExtendableChoice extends AbstractChoice implements ExtendableChoiceInterface
{
    protected static string $translationDomain = 'choices';

    protected static array $identifiers = [];

    public static function setTranslationDomain(string $translationDomain): void
    {
        self::$translationDomain = $translationDomain;
    }

    public static function getTranslationDomain(): string
    {
        return self::$translationDomain;
    }

    public static function setIdentifiers(array $identifiers, bool $override = false): void
    {
        self::$identifiers = $identifiers;
    }

    public static function listIdentifiers(): array
    {
        return self::$identifiers;
    }
}
