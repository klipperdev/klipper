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

use Klipper\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Php Files Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class PhpFilesAdapterTest extends AbstractAdapterTest
{
    public static function tearDownAfterClass(): void
    {
        $fs = new Filesystem();
        $fs->remove(sys_get_temp_dir().'/symfony-cache');
    }

    /**
     * @throws
     */
    protected function setUp(): void
    {
        if (!PhpFilesAdapter::isSupported()) {
            $this->markTestSkipped('OPcache is not enabled');
        }

        $this->adapter = new PhpFilesAdapter('', 0);
        $this->adapter->clear();
    }

    public function testClearByPrefix(): void
    {
        $key1 = static::PREFIX_1.'foo';
        $value1 = 'bar';

        $key2 = static::PREFIX_2.'foo';
        $value2 = 'bar';

        $this->assertFalse($this->adapter->hasItem($key1));
        $this->assertFalse($this->adapter->hasItem($key2));

        // get
        $item1 = $this->adapter->getItem($key1);
        $this->assertInstanceOf(CacheItem::class, $item1);
        $this->assertFalse($item1->isHit());
        $this->assertNull($item1->get());

        $item2 = $this->adapter->getItem($key2);
        $this->assertInstanceOf(CacheItem::class, $item2);
        $this->assertFalse($item2->isHit());
        $this->assertNull($item2->get());

        // set
        $item1->set($value1);
        $this->adapter->save($item1);

        $item2->set($value2);
        $this->adapter->save($item2);

        // refresh
        $item1 = $this->adapter->getItem($key1);
        $this->assertTrue($item1->isHit());
        $this->assertSame($value1, $item1->get());

        $item2 = $this->adapter->getItem($key2);
        $this->assertTrue($item2->isHit());
        $this->assertSame($value2, $item2->get());

        // clear
        $res = $this->adapter->clearByPrefix(static::PREFIX_1);
        $this->assertTrue($res);

        $item1 = $this->adapter->getItem($key1);
        $this->assertFalse($item1->isHit());
        $this->assertNull($item1->get());

        // check
        $this->assertFalse($this->adapter->hasItem($key1));
        $this->assertFalse($this->adapter->hasItem($key2));
    }

    public function testClearByPrefixWithDeferredItem(): void
    {
        $key1 = static::PREFIX_1.'foo';
        $value1 = 'bar';

        $key2 = static::PREFIX_2.'foo';

        $this->assertFalse($this->adapter->hasItem($key1));
        $this->assertFalse($this->adapter->hasItem($key2));

        // get
        $item1 = $this->adapter->getItem($key1);
        $this->assertInstanceOf(CacheItem::class, $item1);
        $this->assertFalse($item1->isHit());
        $this->assertNull($item1->get());

        $item2 = $this->adapter->getItem($key2);
        $this->assertInstanceOf(CacheItem::class, $item2);
        $this->assertFalse($item2->isHit());
        $this->assertNull($item2->get());

        // set
        $item1->set($value1);
        $this->adapter->save($item1);

        $this->adapter->saveDeferred($item2);

        // clear
        $res = $this->adapter->clearByPrefix(static::PREFIX_2);
        $this->assertTrue($res);

        $item1 = $this->adapter->getItem($key1);
        $this->assertFalse($item1->isHit());
        $this->assertNull($item1->get());

        // check
        $this->assertFalse($this->adapter->hasItem($key1));
        $this->assertFalse($this->adapter->hasItem($key2));
    }
}
