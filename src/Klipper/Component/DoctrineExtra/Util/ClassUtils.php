<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtra\Util;

/**
 * Class related functionality for objects that might or not be proxy objects at the moment.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ClassUtils
{
    /**
     * Marker for Proxy class names.
     */
    public const MARKER = '__CG__';

    /**
     * Length of the proxy marker.
     */
    public const MARKER_LENGTH = 6;

    /**
     * Gets the real class name of a class name that could be a proxy.
     *
     * @param string $class The class name
     */
    public static function getRealClass(string $class): string
    {
        if (false === $pos = strrpos($class, '\\'.static::MARKER.'\\')) {
            return $class;
        }

        return substr($class, $pos + static::MARKER_LENGTH + 2);
    }

    /**
     * Gets the real class name of an object.
     *
     * @param object $object The object
     */
    public static function getClass(object $object): string
    {
        return self::getRealClass(\get_class($object));
    }
}
