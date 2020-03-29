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

use Klipper\Component\Config\ConfigResource;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ConfigResourceTest extends TestCase
{
    public function testGetters(): void
    {
        $resource = new ConfigResource('directory_path', 'type');

        static::assertSame('directory_path', $resource->getResource());
        static::assertSame('type', $resource->getType());
    }

    public function testToString(): void
    {
        $resource = new ConfigResource('directory_path', 'type');

        static::assertSame('directory_path [type])', (string) $resource);
    }
}
