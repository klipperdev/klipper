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
 * Interface of currencyable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface CurrencyableInterface
{
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
