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
use Klipper\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * PHP Array Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class PhpArrayAdapterTest extends AbstractAdapterTest
{
    protected ?Filesystem $fs = null;

    private static string $file = '';

    public static function setupBeforeClass(): void
    {
        self::$file = sys_get_temp_dir().'/symfony-cache/php-array-adapter-test.php';
    }

    protected function setUp(): void
    {
        $this->fs = new Filesystem();
        $this->adapter = new PhpArrayAdapter(self::$file, new ArrayAdapter());
        $this->adapter->clear();

        $this->fs->remove(sys_get_temp_dir().'/symfony-cache');
    }

    protected function tearDown(): void
    {
        $this->fs->remove(sys_get_temp_dir().'/symfony-cache');
    }

    /**
     * @throws
     */
    public function testInitialization(): void
    {
        $this->adapter = new PhpArrayAdapter(self::$file, new ArrayAdapter());
        $this->adapter->clearByPrefix('foo');

        static::assertFalse($this->adapter->hasItem('foo_bar'));
    }

    /**
     * @throws
     */
    public function testWarmUp(): void
    {
        $values = [
            self::PREFIX_1.'foo' => 'bar1',
            self::PREFIX_2.'foo' => 'bar2',
        ];

        static::assertFileDoesNotExist(self::$file);
        $this->adapter->warmUp($values);
        static::assertFileExists(self::$file);

        $item1 = $this->adapter->getItem(self::PREFIX_1.'foo');
        static::assertTrue($item1->isHit());
        static::assertSame('bar1', $item1->get());

        $item2 = $this->adapter->getItem(self::PREFIX_2.'foo');
        static::assertTrue($item2->isHit());
        static::assertSame('bar2', $item2->get());

        $res = $this->adapter->clearByPrefix(self::PREFIX_1);
        static::assertTrue($res);

        static::assertFalse($this->adapter->hasItem(self::PREFIX_1.'foo'));
        static::assertTrue($this->adapter->hasItem(self::PREFIX_2.'foo'));
    }
}
