<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Tests\Scope;

use Klipper\Component\SecurityOauth\Scope\Loader\SimpleScopeLoader;
use Klipper\Component\SecurityOauth\Scope\ScopeRegistry;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ScopeRegistryTest extends TestCase
{
    public function testGetScopes(): void
    {
        $expected = [
            'common/scope',
            'loader1/scope1',
            'loader1/scope2',
            'loader2/scope1',
            'loader2/scope2',
            'loader2/scope3',
            'loader3/scope1',
        ];

        $registry = new ScopeRegistry([
            new SimpleScopeLoader([
                'common/scope',
                'loader1/scope1',
                'loader1/scope2',
            ]),
            new SimpleScopeLoader([
                'loader2/scope1',
                'loader2/scope2',
                'loader2/scope3',
            ]),
            new SimpleScopeLoader([
                'common/scope',
                'loader3/scope1',
            ]),
        ]);

        static::assertSame($expected, $registry->getScopes());
    }
}
