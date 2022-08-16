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

use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter as BaseRedisTagAwareAdapter;

/**
 * Redis Tag Aware Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RedisTagAwareAdapter extends BaseRedisTagAwareAdapter implements AdapterInterface
{
    public function clearByPrefix(string $prefix): bool
    {
        return $this->clear($prefix);
    }

    public function clearByPrefixes(array $prefixes): bool
    {
        $ok = true;

        foreach ($prefixes as $prefix) {
            $ok = $this->clear($prefix) && $ok;
        }

        return $ok;
    }
}
