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

use Klipper\Component\Cache\Adapter\PdoAdapter;

/**
 * Pdo Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class PdoAdapterTest extends AbstractAdapterTest
{
    protected string $dbFile = '';

    /**
     * @throws
     */
    protected function setUp(): void
    {
        if (!\extension_loaded('pdo_sqlite')) {
            static::markTestSkipped('Extension pdo_sqlite required.');
        }

        $this->dbFile = tempnam(sys_get_temp_dir(), 'st_sqlite_cache');

        $this->adapter = new PdoAdapter(
            'sqlite:'.$this->dbFile,
            '',
            0
        );
        $this->adapter->createTable();
        $this->adapter->clear();
    }

    protected function tearDown(): void
    {
        @unlink($this->dbFile);
    }
}
