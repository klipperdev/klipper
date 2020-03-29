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

use Klipper\Component\Config\Loader\PhpParser;
use Klipper\Component\Config\Tests\Fixtures\Model\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class PhpParserTest extends TestCase
{
    public function testExtractClasses(): void
    {
        $ref = new \ReflectionClass(MockObject::class);

        $classes = PhpParser::extractClasses($ref->getFileName());
        $expected = [
            MockObject::class,
        ];

        static::assertSame($expected, $classes);
    }
}
