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
interface ExtendableChoiceInterface extends ChoiceInterface
{
    /**
     * Set the map of identifiers.
     *
     * @param array $identifiers The map of values with labels
     * @param bool  $override    Override the values
     */
    public static function setIdentifiers(array $identifiers, bool $override = false): void;

    /**
     * Set the translation domain.
     *
     * @param string $translationDomain The translation domain
     */
    public static function setTranslationDomain(string $translationDomain): void;
}
