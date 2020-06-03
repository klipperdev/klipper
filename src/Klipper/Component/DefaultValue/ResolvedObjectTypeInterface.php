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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A wrapper for a object default value type and its extensions.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ResolvedObjectTypeInterface
{
    /**
     * Returns the class name of the type.
     *
     * @return string The type name
     */
    public function getClass(): string;

    /**
     * Returns the parent type.
     *
     * @return ResolvedObjectTypeInterface The parent type or null
     */
    public function getParent(): ResolvedObjectTypeInterface;

    /**
     * Returns the wrapped object default value type.
     *
     * @return ObjectTypeInterface The wrapped object default value type
     */
    public function getInnerType(): ObjectTypeInterface;

    /**
     * Returns the extensions of the wrapped object default value type.
     *
     * @return ObjectTypeExtensionInterface[] An array of {@link ObjectTypeExtensionInterface} instances
     */
    public function getTypeExtensions(): iterable;

    /**
     * Creates a new object default value builder for this type.
     *
     * @param ObjectFactoryInterface $factory The object default value factory
     * @param array                  $options The builder options
     *
     * @return ObjectBuilderInterface The created object default value builder
     */
    public function createBuilder(ObjectFactoryInterface $factory, array $options = []): ObjectBuilderInterface;

    /**
     * Constructs a new object instance.
     *
     * @param ObjectBuilderInterface $builder The builder to configure
     * @param array                  $options The builder options
     *
     * @return object The new object instance
     */
    public function newInstance(ObjectBuilderInterface $builder, array $options): ?object;

    /**
     * Configures a object default value builder for the type hierarchy.
     *
     * @param ObjectBuilderInterface $builder The builder to configure
     * @param array                  $options The options used for the configuration
     */
    public function buildObject(ObjectBuilderInterface $builder, array $options): void;

    /**
     * Finishes a object default value builder for the type hierarchy.
     *
     * @param ObjectBuilderInterface $builder The builder to configure
     * @param array                  $options The options used for the configuration
     */
    public function finishObject(ObjectBuilderInterface $builder, array $options): void;

    /**
     * Returns the configured options resolver used for this type.
     *
     * @return OptionsResolver The options resolver
     */
    public function getOptionsResolver(): OptionsResolver;
}
