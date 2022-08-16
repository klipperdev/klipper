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

use Klipper\Component\Cache\Adapter\ArrayAdapter;
use Klipper\Component\Cache\Adapter\Psr16Adapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Cache\Psr16Cache;

/**
 * PSR 16 Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class Psr16AdapterTest extends AbstractAdapterTest
{
    protected function setUp(): void
    {
        $this->adapter = new Psr16Adapter(new Psr16Cache(new ArrayAdapter()), static::PREFIX_GLOBAL, 0);
        $this->adapter->clear();
    }

    /**
     * @throws
     */
    public function testClearByPrefix(): void
    {
        $key1 = static::PREFIX_1.'foo';
        $value1 = 'bar';

        $key2 = static::PREFIX_2.'foo';
        $value2 = 'bar';

        static::assertFalse($this->adapter->hasItem($key1));
        static::assertFalse($this->adapter->hasItem($key2));

        // get
        $item1 = $this->adapter->getItem($key1);
        static::assertInstanceOf(CacheItem::class, $item1);
        static::assertFalse($item1->isHit());
        static::assertNull($item1->get());

        $item2 = $this->adapter->getItem($key2);
        static::assertInstanceOf(CacheItem::class, $item2);
        static::assertFalse($item2->isHit());
        static::assertNull($item2->get());

        // set
        $item1->set($value1);
        static::assertTrue($this->adapter->save($item1));

        $item2->set($value2);
        static::assertTrue($this->adapter->save($item2));

        // refresh
        $item1 = $this->adapter->getItem($key1);
        static::assertTrue($item1->isHit());
        static::assertSame($value1, $item1->get());

        $item2 = $this->adapter->getItem($key2);
        static::assertTrue($item2->isHit());
        static::assertSame($value2, $item2->get());

        // clear
        $res = $this->adapter->clearByPrefix(static::PREFIX_1);
        static::assertTrue($res);

        $item1 = $this->adapter->getItem($key1);
        static::assertFalse($item1->isHit());
        static::assertNull($item1->get());

        // check
        static::assertFalse($this->adapter->hasItem($key1));
        // False because the cache pool clear all items
        static::assertFalse($this->adapter->hasItem($key2));
    }

    /**
     * @throws
     */
    public function testClearByPrefixWithDeferredItem(): void
    {
        $key1 = static::PREFIX_1.'foo';
        $value1 = 'bar';

        $key2 = static::PREFIX_2.'foo';

        static::assertFalse($this->adapter->hasItem($key1));
        static::assertFalse($this->adapter->hasItem($key2));

        // get
        $item1 = $this->adapter->getItem($key1);
        static::assertInstanceOf(CacheItem::class, $item1);
        static::assertFalse($item1->isHit());
        static::assertNull($item1->get());

        $item2 = $this->adapter->getItem($key2);
        static::assertInstanceOf(CacheItem::class, $item2);
        static::assertFalse($item2->isHit());
        static::assertNull($item2->get());

        // set
        $item1->set($value1);
        static::assertTrue($this->adapter->save($item1));

        $this->adapter->saveDeferred($item2);

        // clear
        $res = $this->adapter->clearByPrefix(static::PREFIX_2);
        static::assertTrue($res);

        $item1 = $this->adapter->getItem($key1);
        // False because the cache pool clear all items
        static::assertFalse($item1->isHit());
        static::assertNull($item1->get());

        // check
        // False because the cache pool clear all items
        static::assertFalse($this->adapter->hasItem($key1));
        static::assertFalse($this->adapter->hasItem($key2));
    }
}
