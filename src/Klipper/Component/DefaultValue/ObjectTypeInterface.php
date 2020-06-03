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
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ObjectTypeInterface
{
    /**
     * Constructs a new object instance.
     */
    public function newInstance(ObjectBuilderInterface $builder, array $options): ?object;

    /**
     * Builds the object default value.
     *
     * This method is called for each type in the hierarchy starting object the
     * top most type. Type extensions can further modify the object.
     */
    public function buildObject(ObjectBuilderInterface $builder, array $options): void;

    /**
     * Finishes the object.
     *
     * This method is called for each type in the hierarchy ending object the
     * top most type. Type extensions can further modify the object.
     */
    public function finishObject(ObjectBuilderInterface $builder, array $options): void;

    /**
     * Configures the options for this type.
     */
    public function configureOptions(OptionsResolver $resolver): void;

    /**
     * Returns the name of the parent type.
     *
     * @return null|string The name of the parent type if any, null otherwise
     */
    public function getParent(): ?string;

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getClass(): string;
}
