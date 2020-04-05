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
 * Timezoneable editable interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface TimezoneableEditableInterface extends TimezoneableInterface
{
    /**
     * Set the timezone.
     *
     * @param null|string $timezone The timezone
     *
     * @return static
     */
    public function setTimezone(?string $timezone);
}
