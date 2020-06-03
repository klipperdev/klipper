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

/**
 * A builder for ObjectFactoryInterface objects.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ObjectFactoryBuilderInterface
{
    /**
     * Sets the factory for creating ResolvedObjectTypeInterface instances.
     *
     * @return static
     */
    public function setResolvedTypeFactory(ResolvedObjectTypeFactoryInterface $resolvedTypeFactory);

    /**
     * Adds an extension to be loaded by the factory.
     *
     * @param ObjectExtensionInterface $extension The extension
     *
     * @return static
     */
    public function addExtension(ObjectExtensionInterface $extension);

    /**
     * Adds a list of extensions to be loaded by the factory.
     *
     * @param array $extensions The extensions
     *
     * @return static
     */
    public function addExtensions(array $extensions);

    /**
     * Adds a object default value type to the factory.
     *
     * @param ObjectTypeInterface $type The object default value type
     *
     * @return static
     */
    public function addType(ObjectTypeInterface $type);

    /**
     * Adds a list of object default value types to the factory.
     *
     * @param array $types The object default value types
     *
     * @return static
     */
    public function addTypes(array $types);

    /**
     * Adds a object default value type extension to the factory.
     *
     * @param ObjectTypeExtensionInterface $typeExtension The object default value type extension
     *
     * @return static
     */
    public function addTypeExtension(ObjectTypeExtensionInterface $typeExtension);

    /**
     * Adds a list of object default value type extensions to the factory.
     *
     * @param array $typeExtensions The object default value type extensions
     *
     * @return static
     */
    public function addTypeExtensions(array $typeExtensions);

    /**
     * Builds and returns the factory.
     *
     * @return ObjectFactoryInterface The object default value factory
     */
    public function getObjectFactory(): ObjectFactoryInterface;
}
