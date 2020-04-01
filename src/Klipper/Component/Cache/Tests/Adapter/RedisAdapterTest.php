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

use Klipper\Component\Cache\Adapter\RedisAdapter;
use Predis\Client;

/**
 * Redis Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class RedisAdapterTest extends AbstractAdapterTest
{
    protected function setUp(): void
    {
        if (!class_exists(Client::class)) {
            static::markTestSkipped('Predis is not installed');
        }

        $redisHost = 'localhost';
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => 1, 'usec' => 0]);
        $result = @socket_connect($socket, $redisHost, 6379);
        socket_close($socket);

        if (!$result) {
            static::markTestSkipped('Redis is not running');
        }

        $redis = RedisAdapter::createConnection('redis://'.$redisHost.'/1', [
            'class' => Client::class,
            'timeout' => 3,
        ]);

        static::assertInstanceOf(Client::class, $redis);

        $this->adapter = new RedisAdapter($redis, static::PREFIX_GLOBAL);
        $this->adapter->clear();
    }
}
