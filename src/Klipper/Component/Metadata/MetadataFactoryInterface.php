<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata;

use Klipper\Component\Metadata\Exception\ObjectMetadataNotFoundException;
use Klipper\Component\Metadata\Loader\ChoiceNameCollection;
use Klipper\Component\Metadata\Loader\ObjectMetadataNameCollection;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface MetadataFactoryInterface
{
    /**
     * Get the managed classes by doctrine.
     */
    public function getManagedClasses(): ObjectMetadataNameCollection;

    /**
     * Check if the class is managed in doctrine.
     *
     * @param string $class The class name
     */
    public function isManagedClass(string $class): bool;

    /**
     * Check if the metadata name is managed in doctrine.
     *
     * @param string $name The metadata name
     */
    public function isManagedByName(string $name): bool;

    /**
     * Get the managed class name defined in doctrine.
     *
     * @param string $class The class name
     *
     * @throws ObjectMetadataNotFoundException When the class is not registered in doctrine
     */
    public function getManagedClass(string $class): string;

    /**
     * Create the metadata.
     *
     * @param string $class The class name
     */
    public function create(string $class): ObjectMetadataInterface;

    /**
     * Create the choice.
     *
     * @param string $name The choice name
     */
    public function createChoice(string $name): ChoiceInterface;

    /**
     * Get the choice names.
     */
    public function getChoiceNames(): ChoiceNameCollection;

    /**
     * Check if the choice is managed.
     *
     * @param string $name The choice name
     */
    public function isChoiceManaged(string $name): bool;
}
