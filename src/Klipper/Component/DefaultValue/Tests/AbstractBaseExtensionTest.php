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
use Klipper\Component\DefaultValue\ObjectTypeExtensionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractBaseExtensionTest extends TestCase
{
    /**
     * @var ObjectExtensionInterface
     */
    protected $extension;

    protected function setUp(): void
    {
        throw new \LogicException('The setUp() method must be overridden');
    }

    protected function tearDown(): void
    {
        $this->extension = null;
    }

    public function testHasType(): void
    {
        $this->assertTrue($this->extension->hasType('Klipper\Component\DefaultValue\Tests\Fixtures\Object\User'));
        $this->assertFalse($this->extension->hasType('Foo'));
    }

    public function testHasTypeExtension(): void
    {
        $this->assertTrue($this->extension->hasTypeExtensions('Klipper\Component\DefaultValue\Tests\Fixtures\Object\User'));
        $this->assertFalse($this->extension->hasTypeExtensions('Foo'));
    }

    public function testGetType(): void
    {
        $type = $this->extension->getType('Klipper\Component\DefaultValue\Tests\Fixtures\Object\User');

        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectTypeInterface', $type);
        $this->assertEquals('Klipper\Component\DefaultValue\Tests\Fixtures\Object\User', $type->getClass());
    }

    public function testGetUnexistingType(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\InvalidArgumentException::class);

        $this->extension->getType('Foo');
    }

    public function testGetTypeExtension(): void
    {
        $exts = $this->extension->getTypeExtensions('Klipper\Component\DefaultValue\Tests\Fixtures\Object\User');

        $this->assertIsArray($exts);
        $this->assertCount(1, $exts);

        /** @var ObjectTypeExtensionInterface $ext */
        $ext = $exts[0];
        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectTypeExtensionInterface', $ext);
        $this->assertEquals('Klipper\Component\DefaultValue\Tests\Fixtures\Object\User', $ext->getExtendedType());
    }
}
