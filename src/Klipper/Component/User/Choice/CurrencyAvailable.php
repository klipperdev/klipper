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

use Klipper\Component\Choice\ExtendableChoiceInterface;
use Klipper\Component\Choice\Util\ChoiceAvailableUtil;
use Symfony\Component\Intl\Currencies;

/**
 * Currency Available.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
final class CurrencyAvailable implements ExtendableChoiceInterface
{
    /**
     * @var array
     */
    public static $availables = [
        'EUR',
    ];

    /**
     * @var null|array
     */
    private static $cache;

    /**
     * {@inheritdoc}
     */
    public static function setTranslationDomain(string $translationDomain): void
    {
        // do nothing
    }

    /**
     * {@inheritdoc}
     */
    public static function getTranslationDomain(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public static function setIdentifiers(array $identifiers, bool $override = false): void
    {
        static::$availables = array_merge($override ? [] : static::$availables, array_values($identifiers));
        static::$cache = null;

        ksort(static::$availables);
    }

    /**
     * {@inheritdoc}
     */
    public static function listIdentifiers(): array
    {
        if (null === static::$cache) {
            $names = Currencies::getNames();
            static::$cache = ChoiceAvailableUtil::generateAvailableList($names, static::$availables);
        }

        return static::$cache;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValues(): array
    {
        return array_keys(static::listIdentifiers());
    }
}
