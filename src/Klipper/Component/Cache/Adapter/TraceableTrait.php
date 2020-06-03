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

use Symfony\Component\Cache\Adapter\TraceableAdapterEvent;

/**
 * Adapter Trait for clear by prefixes.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @property AdapterInterface $pool
 *
 * @method TraceableAdapterEvent start(string $name)
 */
trait TraceableTrait
{
    public function clearByPrefix(string $prefix): bool
    {
        $event = $this->start(__FUNCTION__);
        $event->result['prefix'] = $prefix;

        try {
            return $event->result['result'] = $this->pool instanceof AdapterInterface
                ? $this->pool->clearByPrefix($prefix)
                : $this->pool->clear();
        } finally {
            $event->end = microtime(true);
        }
    }

    public function clearByPrefixes(array $prefixes): bool
    {
        $event = $this->start(__FUNCTION__);
        $event->result['prefixes'] = $prefixes;

        try {
            return $event->result['result'] = $this->pool instanceof AdapterInterface
                ? $this->pool->clearByPrefixes($prefixes)
                : $this->pool->clear();
        } finally {
            $event->end = microtime(true);
        }
    }
}
