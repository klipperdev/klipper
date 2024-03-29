<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue;

use Klipper\Component\DefaultValue\Exception\BadMethodCallException;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * The configuration of a object.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ObjectConfigInterface
{
    /**
     * Returns the object default value types used to construct the object.
     *
     * @return ResolvedObjectTypeInterface The object default value's type
     */
    public function getType(): ResolvedObjectTypeInterface;

    /**
     * Returns the properties of the object.
     *
     * @return array An array of key-value combinations
     */
    public function getProperties(): array;

    /**
     * Returns whether the property with the given name exists.
     *
     * @param string $name The property name
     *
     * @return bool Whether the property exists
     */
    public function hasProperty(string $name): bool;

    /**
     * Returns the value of the given property.
     *
     * @param string $name The property name
     *
     * @return mixed The property value
     *
     * @throws InvalidArgumentException
     * @throws BadMethodCallException   When the data is empty
     */
    public function getProperty(string $name);

    /**
     * Returns the data of the object default value.
     *
     * @return mixed The initial data of object default value
     */
    public function getData();

    /**
     * Returns the class of the object default value data .
     *
     * @return string The data class
     */
    public function getDataClass(): ?string;

    /**
     * Returns all options passed during the construction of the block.
     *
     * @return array The passed options
     */
    public function getOptions(): array;

    /**
     * Returns whether a specific option exists.
     *
     * @param string $name The option name
     *
     * @return bool Whether the option exists
     */
    public function hasOption(string $name): bool;

    /**
     * Returns the value of a specific option.
     *
     * @param string $name    The option name
     * @param mixed  $default The value returned if the option does not exist
     *
     * @return mixed The option value
     */
    public function getOption(string $name, $default = null);
}
