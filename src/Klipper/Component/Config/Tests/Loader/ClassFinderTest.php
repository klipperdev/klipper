<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Tests\Loader;

use Klipper\Component\Config\Loader\ClassFinder;
use Klipper\Component\Config\Tests\Fixtures\Model\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ClassFinderTest extends TestCase
{
    public function testFindClasses(): void
    {
        $finder = new ClassFinder(
            [
                \dirname(__DIR__).'/Fixtures',
            ],
            [
                'Annotation',
                'Cache',
                'Controller',
            ]
        );

        $expected = [
            MockObject::class,
        ];

        static::assertSame($expected, $finder->findClasses());
    }
}
