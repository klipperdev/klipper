<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Model\Traits;

/**
 * Interface of setting.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface SettingInterface
{
    /**
     * Set the timezone.
     *
     * @param null|string $timezone The timezone
     *
     * @return static
     */
    public function setTimezone(?string $timezone);

    /**
     * Get the timezone.
     */
    public function getTimezone(): ?string;

    /**
     * Set the locale.
     *
     * @param null|string $locale The locale
     *
     * @return static
     */
    public function setLocale(?string $locale);

    /**
     * Get the locale.
     */
    public function getLocale(): ?string;

    /**
     * Set the currency.
     *
     * @param null|string $currency The currency
     *
     * @return static
     */
    public function setCurrency(?string $currency);

    /**
     * Get the currency.
     */
    public function getCurrency(): ?string;
}
