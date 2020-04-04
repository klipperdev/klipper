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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface MetadataManagerInterface
{
    /**
     * Get the object metadatas.
     *
     * @return ObjectMetadataInterface[]
     */
    public function all(): array;

    /**
     * Check if the object metadata is present.
     *
     * @param string $class The class name
     */
    public function has(string $class): bool;

    /**
     * Check if the object metadata is present by her name.
     *
     * @param string $name The object name
     */
    public function hasByName(string $name): bool;

    /**
     * Get the object metadata.
     *
     * @param string $class The class name
     *
     * @throws ObjectMetadataNotFoundException When the class is not managed
     */
    public function get(string $class): ObjectMetadataInterface;

    /**
     * Get the object metadata by her name.
     *
     * @param string $name The object name
     *
     * @throws ObjectMetadataNotFoundException When the object name is not managed
     */
    public function getByName(string $name): ObjectMetadataInterface;

    /**
     * Get the choices.
     *
     * @return ChoiceInterface[]
     */
    public function allChoices(): array;

    /**
     * Check if the choice is present.
     *
     * @param string $name The choice name
     */
    public function hasChoice(string $name): bool;

    /**
     * Get the choice.
     *
     * @param string $name The choice name
     */
    public function getChoice(string $name): ChoiceInterface;
}
