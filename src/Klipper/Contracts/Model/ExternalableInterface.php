<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Contracts\Model;

/**
 * Interface of external ids model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ExternalableInterface
{
    /**
     * Get the map of external ids.
     */
    public function getExternalIds(): array;

    /**
     * Check if the external id of the service is defined.
     *
     * @param string $service The service name
     */
    public function hasExternalId(string $service): bool;

    /**
     * Get the external id of the service.
     *
     * @param string $service The service name
     */
    public function getExternalId(string $service): ?string;

    /**
     * Set the external ids of service.
     *
     * @param array $serviceIds The map of service names and service ids
     */
    public function setExternalIds(array $serviceIds): void;

    /**
     * Add the external id of service.
     *
     * @param string $service The service name
     * @param string $id      The service id
     */
    public function addExternalId(string $service, string $id): void;

    /**
     * Remove the external id of service.
     *
     * @param string $service The service name
     */
    public function removeExternalId(string $service): void;
}
