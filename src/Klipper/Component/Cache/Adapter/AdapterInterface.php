<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Cache\Adapter;

use Symfony\Component\Cache\Adapter\AdapterInterface as BaseAdapterInterface;

/**
 * Cache Adapter Interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface AdapterInterface extends BaseAdapterInterface
{
    /**
     * Deletes all items identified by the prefix in the pool.
     *
     * @param string $prefix The prefix
     *
     * @return bool True if the pool was successfully cleared. False if there was an error
     */
    public function clearByPrefix(string $prefix): bool;

    /**
     * Deletes all items identified by the prefixes in the pool.
     *
     * @param string[] $prefixes The prefixes
     *
     * @return bool True if the pool was successfully cleared. False if there was an error
     */
    public function clearByPrefixes(array $prefixes): bool;
}
