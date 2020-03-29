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
use Klipper\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter as SymfonyArrayAdapter;

/**
 * Tag Aware Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class TagAwareAdapterTest extends AbstractAdapterTest
{
    protected function setUp(): void
    {
        $this->adapter = new TagAwareAdapter(new ArrayAdapter(), new SymfonyArrayAdapter());
        $this->adapter->clear();
    }
}
