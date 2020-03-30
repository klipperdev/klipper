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
use Klipper\Component\DefaultValue\ObjectFactoryBuilder;
use Klipper\Component\DefaultValue\ObjectFactoryBuilderInterface;
use Klipper\Component\DefaultValue\ObjectTypeExtensionInterface;
use Klipper\Component\DefaultValue\ObjectTypeInterface;
use Klipper\Component\DefaultValue\ResolvedObjectTypeFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ObjectFactoryBuilderTest extends TestCase
{
    /**
     * @var ObjectFactoryBuilderInterface
     */
    protected $builder;

    protected function setUp(): void
    {
        $this->builder = new ObjectFactoryBuilder();
    }

    protected function tearDown(): void
    {
        $this->builder = null;
    }

    public function testSetResolvedObjectTypeFactory(): void
    {
        /** @var ResolvedObjectTypeFactoryInterface $typeFactory */
        $typeFactory = $this->getMockBuilder('Klipper\Component\DefaultValue\ResolvedObjectTypeFactoryInterface')->getMock();

        $builder = $this->builder->setResolvedTypeFactory($typeFactory);

        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectFactoryBuilderInterface', $builder);
    }

    public function testAddExtension(): void
    {
        /** @var ObjectExtensionInterface $ext */
        $ext = $this->getMockBuilder('Klipper\Component\DefaultValue\ObjectExtensionInterface')->getMock();

        $builder = $this->builder->addExtension($ext);

        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectFactoryBuilderInterface', $builder);
    }

    public function testAddExtensions(): void
    {
        $exts = [
            $this->getMockBuilder('Klipper\Component\DefaultValue\ObjectExtensionInterface')->getMock(),
        ];

        $builder = $this->builder->addExtensions($exts);

        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectFactoryBuilderInterface', $builder);
    }

    public function testAddType(): void
    {
        /** @var ObjectTypeInterface $type */
        $type = $this->getMockBuilder('Klipper\Component\DefaultValue\ObjectTypeInterface')->getMock();

        $builder = $this->builder->addType($type);

        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectFactoryBuilderInterface', $builder);
    }

    public function testAddTypes(): void
    {
        $types = [
            $this->getMockBuilder('Klipper\Component\DefaultValue\ObjectTypeInterface')->getMock(),
        ];

        $builder = $this->builder->addTypes($types);

        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectFactoryBuilderInterface', $builder);
    }

    public function testAddTypeExtension(): void
    {
        /** @var ObjectTypeExtensionInterface $ext */
        $ext = $this->getMockBuilder('Klipper\Component\DefaultValue\ObjectTypeExtensionInterface')->getMock();

        $builder = $this->builder->addTypeExtension($ext);

        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectFactoryBuilderInterface', $builder);
    }

    public function testAddTypeExtensions(): void
    {
        $exts = [
            $this->getMockBuilder('Klipper\Component\DefaultValue\ObjectTypeExtensionInterface')->getMock(),
        ];

        $builder = $this->builder->addTypeExtensions($exts);

        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectFactoryBuilderInterface', $builder);
    }

    public function testGetObjectFactory(): void
    {
        /** @var ObjectTypeInterface $type */
        $type = $this->getMockBuilder('Klipper\Component\DefaultValue\ObjectTypeInterface')->getMock();
        $this->builder->addType($type);

        $of = $this->builder->getObjectFactory();

        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectFactory', $of);
    }
}
