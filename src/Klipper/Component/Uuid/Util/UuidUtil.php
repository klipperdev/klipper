<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Uuid\Util;

use Ramsey\Uuid\Uuid;

/**
 * Uuid Utils.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class UuidUtil
{
    public const PATTERN_UUID_V4 = '/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/';

    /**
     * Check if the string is a UUID v4.
     *
     * @param string $string The string
     */
    public static function isV4(string $string): bool
    {
        return (bool) preg_match(static::PATTERN_UUID_V4, $string, $m);
    }

    /**
     * Validate the string uuid.
     *
     * @param string $string The string
     */
    public static function validate(string $string): string
    {
        try {
            $string = Uuid::fromString($string)->toString();
        } catch (\Exception $e) {
            $string = Uuid::NIL;
        }

        return $string;
    }
}
