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

use Klipper\Component\DefaultValue\ObjectRegistry;
use Klipper\Component\DefaultValue\ObjectRegistryInterface;
use Klipper\Component\DefaultValue\ResolvedObjectTypeFactory;
use Klipper\Component\DefaultValue\Tests\Fixtures\TestExtension;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ObjectRegistryTest extends TestCase
{
    /**
     * @var ObjectRegistryInterface
     */
    protected $registry;

    protected function setUp(): void
    {
        $this->registry = new ObjectRegistry([
            new TestExtension(),
        ], new ResolvedObjectTypeFactory());
    }

    public function testExtensionUnexpectedTypeException(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\UnexpectedTypeException::class);

        new ObjectRegistry([
            42,
        ], new ResolvedObjectTypeFactory());
    }

    public function testHasTypeObject(): void
    {
        $classname = 'Klipper\Component\DefaultValue\Tests\Fixtures\Object\User';
        $classname2 = 'Klipper\Component\DefaultValue\Tests\Fixtures\Object\UnexistingType';

        static::assertTrue($this->registry->hasType($classname));
        static::assertTrue($this->registry->hasType($classname)); // uses cache in class
        static::assertFalse($this->registry->hasType($classname2));
    }

    public function testGetTypeObject(): void
    {
        $classname = 'Klipper\Component\DefaultValue\Tests\Fixtures\Object\User';
        $resolvedType = $this->registry->getType($classname);

        static::assertInstanceOf('Klipper\Component\DefaultValue\ResolvedObjectTypeInterface', $resolvedType);
        static::assertEquals($classname, $resolvedType->getClass());
    }

    public function testGetDefaultTypeObject(): void
    {
        $classname = 'Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo';
        $resolvedType = $this->registry->getType($classname);

        static::assertInstanceOf('Klipper\Component\DefaultValue\ResolvedObjectTypeInterface', $resolvedType);
        static::assertEquals($classname, $resolvedType->getClass());
        static::assertInstanceOf('Klipper\Component\DefaultValue\Extension\Core\Type\DefaultType', $resolvedType->getInnerType());
    }

    public function testGetTypeObjectUnexpectedTypeException(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\UnexpectedTypeException::class);

        $this->registry->getType(42);
    }

    public function testGetExtensions(): void
    {
        $exts = $this->registry->getExtensions();
        static::assertIsArray($exts);
        static::assertCount(1, $exts);
    }
}
