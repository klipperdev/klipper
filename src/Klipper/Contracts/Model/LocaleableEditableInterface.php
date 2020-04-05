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
 * Interface of localeable editdable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface LocaleableEditableInterface extends LocaleableInterface
{
    /**
     * Set the locale.
     *
     * @param null|string $locale The locale
     *
     * @return static
     */
    public function setLocale(?string $locale);
}
