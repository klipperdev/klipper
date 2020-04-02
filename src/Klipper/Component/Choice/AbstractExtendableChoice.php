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
    /**
     * @var string
     */
    protected static $translationDomain = 'choices';

    /**
     * @var array
     */
    protected static $identifiers = [];

    /**
     * {@inheritdoc}
     */
    public static function setTranslationDomain(string $translationDomain): void
    {
        self::$translationDomain = $translationDomain;
    }

    /**
     * {@inheritdoc}
     */
    public static function getTranslationDomain(): string
    {
        return self::$translationDomain;
    }

    /**
     * {@inheritdoc}
     */
    public static function setIdentifiers(array $identifiers, bool $override = false): void
    {
        self::$identifiers = $identifiers;
    }

    /**
     * {@inheritdoc}
     */
    public static function listIdentifiers(): array
    {
        return self::$identifiers;
    }
}
