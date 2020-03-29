<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Cache\Tests\Adapter;

use Klipper\Component\Cache\Adapter\MemcachedAdapter;

/**
 * Memcached Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class MemcachedAdapterTest extends AbstractAdapterTest
{
    protected function setUp(): void
    {
        if (!MemcachedAdapter::isSupported()) {
            $this->markTestSkipped('Extension memcached >=2.2.0 required.');
        }

        $client = new \Memcached();
        $client->addServers([[
            getenv('MEMCACHED_HOST') ?: '127.0.0.1',
            getenv('MEMCACHED_PORT') ?: 11211,
        ]]);

        $this->adapter = new MemcachedAdapter($client, str_replace('\\', '.', __CLASS__), 0);
        $this->adapter->clear();
    }
}
