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

/**
 * Array Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ArrayAdapterTest extends AbstractAdapterTest
{
    protected function setUp(): void
    {
        $this->adapter = new ArrayAdapter(0);
        $this->adapter->clear();
    }
}
