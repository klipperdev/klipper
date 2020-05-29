<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Tests\Scope\Loader;

use Klipper\Component\SecurityOauth\Scope\Loader\SimpleScopeLoader;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class SimpleScopeLoaderTest extends TestCase
{
    public function testLoad(): void
    {
        $expected = [
            'scope1',
            'scope2',
        ];

        $loader = new SimpleScopeLoader([
            'scope1',
            'scope2',
        ]);

        static::assertSame($expected, $loader->load());
    }
}
