<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Contracts\Model;

/**
 * Interface to manage personal translation.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface TranslatableInterface
{
    /**
     * Set the locale of translatable.
     *
     * @param string $locale The locale
     *
     * @return static
     */
    public function setTranslatableLocale(string $locale);

    /**
     * Set the available locales.
     *
     * @param string[] $availableLocales The available locales
     *
     * @return static
     */
    public function setAvailableLocales(array $availableLocales);

    /**
     * Get the available locales.
     *
     * @return string[]
     */
    public function getAvailableLocales(): array;

    /**
     * Remove all translated fields for a specific locale.
     * Return the removed translated fields.
     *
     * @return object[]
     */
    public function removeTranslationFields(string $locale): array;
}
