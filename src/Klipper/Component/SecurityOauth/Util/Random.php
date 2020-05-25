<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Util;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Random
{
    /**
     * @throws
     */
    public static function generateToken(int $length = 32, int $fromBase = 16, int $toBase = 36): string
    {
        $bytes = random_bytes($length);

        return base_convert(bin2hex($bytes), $fromBase, $toBase);
    }
}
