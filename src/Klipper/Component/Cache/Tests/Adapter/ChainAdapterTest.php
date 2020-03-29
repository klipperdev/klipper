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
use Klipper\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter as SymfonyArrayAdapter;

/**
 * Chain Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ChainAdapterTest extends AbstractAdapterTest
{
    protected function setUp(): void
    {
        $this->adapter = new ChainAdapter([
            new SymfonyArrayAdapter(),
            new ArrayAdapter(),
        ]);
        $this->adapter->clear();
    }
}
