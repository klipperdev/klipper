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

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class MetadataUtil
{
    /**
     * Generate the unique name.
     *
     * @param string $name The name
     */
    public static function getName(string $name): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
    }

    /**
     * Generate the short name of domain with the class name.
     */
    public static function generateShortName(string $class): string
    {
        $pos = strrpos($class, '\\');
        $pos = false !== $pos ? $pos + 1 : 0;
        $name = substr($class, $pos);
        if (false !== $pos = strrpos($name, 'Interface')) {
            $name = substr($name, 0, $pos);
        }

        return $name;
    }

    /**
     * Generate the unique name of object.
     *
     * @param string $class The class name
     */
    public static function getObjectName(string $class): string
    {
        return static::getName(static::generateShortName($class));
    }

    /**
     * Generate the label.
     *
     * @param string $label The label
     */
    public static function getLabel(string $label): string
    {
        return str_replace('_', ' ', ucfirst(preg_replace('/(?<!^)[A-Z]/', ' $0', $label)));
    }

    /**
     * Generate the label of object.
     *
     * @param string $class The class name
     */
    public static function getObjectLabel(string $class): string
    {
        return static::getLabel(static::generateShortName($class));
    }

    /**
     * Check if the field value of metadata builder is a default value.
     *
     * @param PropertyAccessorInterface $accessor The property accessor
     * @param object                    $builder  The metadata builder
     * @param string                    $field    The field name
     */
    public static function isDefaultValue(PropertyAccessorInterface $accessor, object $builder, string $field): bool
    {
        return null === $accessor->getValue($builder, $field);
    }

    /**
     * Check if the field can be overridden.
     *
     * @param PropertyAccessorInterface $accessor The property accessor
     * @param object                    $builder  The metadata builder
     * @param string                    $field    The field name
     */
    public static function canBeOverridden(PropertyAccessorInterface $accessor, object $builder, string $field): bool
    {
        return $accessor->isWritable($builder, $field) && static::isDefaultValue($accessor, $builder, $field);
    }

    /**
     * Convert the default sortable string value into a map.
     *
     * @param null|array|string $value The default sortable value
     */
    public static function getDefaultSortable($value): ?array
    {
        if (\is_string($value) && !empty($value)) {
            $fields = explode(',', $value);
            $value = [];

            foreach ($fields as $field) {
                $exp = explode(':', $field);
                $field = trim($exp[0]);
                $value[$field] = isset($exp[1]) ? trim($exp[1]) : 'asc';
            }
        }

        return \is_array($value) && !empty($value) ? $value : null;
    }

    /**
     * Convert the default sortable string value into a map.
     *
     * @param null|array|string $value The default sortable value
     */
    public static function getStringList($value): ?array
    {
        if (\is_string($value) && !empty($value)) {
            $value = array_map('trim', explode(',', $value));
        }

        return \is_array($value) && !empty($value) ? $value : null;
    }

    /**
     * Get the translated message.
     *
     * @param TranslatorInterface $translator        The translator
     * @param null|string         $message           The message
     * @param null|string         $translationDomain The translation domain
     * @param null|string         $name              The name
     */
    public static function getTrans(TranslatorInterface $translator, ?string $message, ?string $translationDomain, ?string $name = null): ?string
    {
        if (null !== $message && null !== $translationDomain) {
            $params = [];

            if (null !== $name) {
                $params['{{ name }}'] = $name;
            }

            $message = $translator->trans($message, $params, $translationDomain);
        }

        return null === $message && null !== $name
            ? str_replace(['-', '_'], ' ', ucfirst($name))
            : $message;
    }
}
