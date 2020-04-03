<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Object\Util;

/**
 * Class Utils.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class ClassUtil
{
    /**
     * Check the object or class name is instance of the class name.
     *
     * @param object|\ReflectionClass|string $object The object instance or the reflection class or the class name
     * @param string                         $class  The class name
     */
    public static function isInstanceOf($object, string $class): bool
    {
        if ($object instanceof \ReflectionClass) {
            $object = $object->getName();
        }

        return is_a($object, $class, true);
    }

    /**
     * Check the object or class name is one instances of the class names.
     *
     * @param object|\ReflectionClass|string $object  The object instance or the reflection class or the class name
     * @param string[]                       $classes The class names
     */
    public static function isOneInstancesOf($object, array $classes): bool
    {
        foreach ($classes as $class) {
            if (static::isInstanceOf($object, $class)) {
                return true;
            }
        }

        return false;
    }
}
