<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue\Tests;

use Klipper\Component\DefaultValue\ObjectExtensionInterface;
use Klipper\Component\DefaultValue\Tests\Fixtures\TestExpectedExtension;
use Klipper\Component\DefaultValue\Tests\Fixtures\TestExtension;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class AbstractExtensionTest extends TestCase
{
    public function testGetUnexistingType(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\InvalidArgumentException::class);

        /** @var ObjectExtensionInterface $ext */
        $ext = $this->getMockForAbstractClass('Klipper\Component\DefaultValue\AbstractExtension');
        $ext->getType('unexisting_type');
    }

    public function testInitLoadTypeException(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\UnexpectedTypeException::class);

        $ext = new TestExpectedExtension();
        $ext->getType('unexisting_type');
    }

    public function testInitLoadTypeExtensionException(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\UnexpectedTypeException::class);

        $ext = new TestExpectedExtension();
        $ext->getTypeExtensions('unexisting_type');
    }

    public function testGetEmptyTypeExtension(): void
    {
        /** @var ObjectExtensionInterface $ext */
        $ext = $this->getMockForAbstractClass('Klipper\Component\DefaultValue\AbstractExtension');
        $typeExts = $ext->getTypeExtensions('unexisting_type_extension');

        $this->assertIsArray($typeExts);
        $this->assertCount(0, $typeExts);
    }

    public function testGetType(): void
    {
        $ext = new TestExtension();
        $type = $ext->getType('Klipper\Component\DefaultValue\Tests\Fixtures\Object\User');

        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectTypeInterface', $type);
    }

    public function testHasType(): void
    {
        $ext = new TestExtension();

        $this->assertTrue($ext->hasType('Klipper\Component\DefaultValue\Tests\Fixtures\Object\User'));
    }

    public function testGetTypeExtensions(): void
    {
        $ext = new TestExtension();
        $typeExts = $ext->getTypeExtensions('Klipper\Component\DefaultValue\Tests\Fixtures\Object\User');

        $this->assertIsArray($typeExts);
        $this->assertCount(1, $typeExts);
        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectTypeExtensionInterface', $typeExts[0]);
    }

    public function testHasTypeExtensions(): void
    {
        $ext = new TestExtension();

        $this->assertTrue($ext->hasTypeExtensions('Klipper\Component\DefaultValue\Tests\Fixtures\Object\User'));
    }
}
