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

use Klipper\Component\Cache\Adapter\NullAdapter;
use Klipper\Component\Cache\Adapter\TraceableAdapter;
use Symfony\Component\Cache\Adapter\NullAdapter as SymfonyNullAdapter;

/**
 * Traceable Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class TraceableAdapterTest extends AbstractAdapterTest
{
    protected function setUp(): void
    {
        $this->adapter = new TraceableAdapter(new NullAdapter());
        $this->adapter->clear();
    }

    public function testClearByPrefix(): void
    {
        $res = $this->adapter->clearByPrefix(static::PREFIX_1);
        $this->assertTrue($res);
    }

    public function testClearByPrefixWithDeferredItem(): void
    {
        $res = $this->adapter->clearByPrefix(static::PREFIX_1);
        $this->assertTrue($res);
    }

    public function getAdapters(): array
    {
        return [
            [new TraceableAdapter(new NullAdapter())],
            [new TraceableAdapter(new SymfonyNullAdapter())],
        ];
    }

    /**
     * @dataProvider getAdapters
     *
     * @param TraceableAdapter $adapter The adapter
     */
    public function testClearByPrefixWithDifferentAdapter(TraceableAdapter $adapter): void
    {
        $res = $adapter->clearByPrefix(static::PREFIX_1);
        $this->assertTrue($res);
    }

    /**
     * @dataProvider getAdapters
     *
     * @param TraceableAdapter $adapter The adapter
     */
    public function testClearByPrefixesWithDifferentAdapter(TraceableAdapter $adapter): void
    {
        $res = $adapter->clearByPrefixes([static::PREFIX_1, static::PREFIX_2]);
        $this->assertTrue($res);
    }
}
