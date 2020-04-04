<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Util;

use Klipper\Component\Metadata\Exception\InvalidArgumentException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class BuilderUtil
{
    /**
     * Merge the values of new object in the primary object if the field values are null.
     *
     * @param object $object    The primary object
     * @param object $newObject The new object
     */
    public static function mergeValues(object $object, object $newObject): void
    {
        $methods = get_class_methods($object);

        foreach ($methods as $method) {
            if (0 === strpos($method, 'set')) {
                static::mergeValue($object, $newObject, $method);
            }
        }
    }

    /**
     * Merge the values of new object in the primary object if the field value is null.
     *
     * @param object $object    The primary object
     * @param object $newObject The new object
     * @param string $setter    The setter method name
     */
    public static function mergeValue(object $object, object $newObject, string $setter): void
    {
        $newValue = null;
        $getter = null;

        foreach (['get', 'is', 'has'] as $accessor) {
            $getter = $accessor.substr($setter, 3);

            if (method_exists($newObject, $getter)) {
                $newValue = $newObject->{$getter}();

                break;
            }
        }

        if (null !== $newValue && null === $object->{$getter}()) {
            $object->{$setter}($newValue);
        }
    }

    /**
     * Validate the required properties of the object.
     *
     * @param object $object             The object
     * @param array  $requiredProperties The required property names
     */
    public static function validate(object $object, array $requiredProperties): void
    {
        foreach ($requiredProperties as $requiredProperty) {
            $value = null;
            $getter = null;

            foreach (['get', 'is', 'has'] as $accessor) {
                $getter = $accessor.ucfirst($requiredProperty);

                if (method_exists($object, $getter)) {
                    $value = $object->{$getter}();

                    break;
                }
            }

            if (null === $value) {
                throw new InvalidArgumentException(sprintf(
                    'The "%s" property of the "%s" instance do not be null to build the metadata',
                    $requiredProperty,
                    \get_class($object)
                ));
            }
        }
    }
}
