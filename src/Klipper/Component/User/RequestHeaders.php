<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\User;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class RequestHeaders
{
    /**
     * The timezone request header.
     *
     * @var string
     */
    public const TIMEZONE = 'X-Timezone';
}
