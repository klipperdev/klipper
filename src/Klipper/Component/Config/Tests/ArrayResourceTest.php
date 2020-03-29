<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Tests;

use Klipper\Component\Config\ArrayResource;
use Klipper\Component\Config\ConfigResource;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ArrayResourceTest extends TestCase
{
    public function testToString(): void
    {
        $resource = new ArrayResource([
            new ConfigResource('directory_path', 'type'),
        ]);

        static::assertSame('Klipper\Component\Config\ArrayResource(directory_path [type]))', (string) $resource);
    }

    public function testAdd(): void
    {
        $resource = new ArrayResource([
            new ConfigResource('directory_path', 'type'),
        ]);

        static::assertCount(1, $resource->all());

        $resource->add('.', 'config');

        static::assertCount(2, $resource->all());
        static::assertCount(2, $resource->getIterator());
    }
}
