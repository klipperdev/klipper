<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Resource\Domain;

use Klipper\Component\Resource\Exception\InvalidArgumentException;

/**
 * A resource domain factory interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface DomainFactoryInterface
{
    /**
     * Add the resolve targets.
     *
     * @param array $resolveTargets The resolve targets
     */
    public function addResolveTargets(array $resolveTargets): DomainFactoryInterface;

    /**
     * Check if the class is managed by doctrine.
     *
     * @param string $class The class name
     */
    public function isManagedClass(string $class): bool;

    /**
     * Get the managed class name defined in doctrine.
     *
     * @throws InvalidArgumentException When the class is not registered in doctrine
     */
    public function getManagedClass(string $class): string;

    /**
     * Create a resource domain.
     *
     * @param string $class The class name
     *
     * @throws InvalidArgumentException When the class is not registered in doctrine
     */
    public function create(string $class): DomainInterface;

    /**
     * Clear all managers.
     */
    public function clear(): void;
}
