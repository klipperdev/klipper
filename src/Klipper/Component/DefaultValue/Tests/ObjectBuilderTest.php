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

use Klipper\Component\DefaultValue\ObjectBuilder;
use Klipper\Component\DefaultValue\ObjectBuilderInterface;
use Klipper\Component\DefaultValue\ObjectFactoryInterface;
use Klipper\Component\DefaultValue\ResolvedObjectType;
use Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo;
use Klipper\Component\DefaultValue\Tests\Fixtures\Type\FooCompletType;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ObjectBuilderTest extends TestCase
{
    /**
     * @var ObjectBuilderInterface
     */
    protected $builder;

    protected function setUp(): void
    {
        $options = [
            'bar' => 'hello world',
        ];
        /** @var ObjectFactoryInterface $factory */
        $factory = $this->getMockBuilder('Klipper\Component\DefaultValue\ObjectFactoryInterface')->getMock();
        $type = new FooCompletType();
        $rType = new ResolvedObjectType($type);

        $this->builder = new ObjectBuilder($factory, $options);
        $this->builder->setType($rType);
    }

    protected function tearDown(): void
    {
        $this->builder = null;
    }

    public function testGetObjectFactory(): void
    {
        $this->assertInstanceOf('Klipper\Component\DefaultValue\ObjectFactoryInterface', $this->builder->getObjectFactory());
    }

    public function testGetObjectWithoutData(): void
    {
        $instance = $this->builder->getObject();

        $this->assertInstanceOf('Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo', $instance);
        $this->assertEquals('hello world', $instance->getBar());
    }

    public function testGetObjectWithoutDataWithEditionOnFinishMethod(): void
    {
        $options = [
            'bar' => 'the answer to life, the universe, and everything',
        ];
        /** @var ObjectFactoryInterface $factory */
        $factory = $this->getMockBuilder('Klipper\Component\DefaultValue\ObjectFactoryInterface')->getMock();
        $type = new FooCompletType();
        $rType = new ResolvedObjectType($type);

        $this->builder = new ObjectBuilder($factory, $options);
        $this->builder->setType($rType);

        $instance = $this->builder->getObject();

        $this->assertInstanceOf('Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo', $instance);
        $this->assertEquals('42', $instance->getBar());
    }

    public function testGetObjectWithData(): void
    {
        $data = new Foo();
        $data->setBar('new value');
        $this->builder->setData($data);
        $instance = $this->builder->getObject();

        $this->assertEquals($data, $instance);
        $this->assertEquals('new value', $instance->getBar());
    }

    public function testGetObjectWithDataWithEditionOnFinishMethod(): void
    {
        $data = new Foo();
        $data->setBar('the answer to life, the universe, and everything');
        $this->builder->setData($data);
        $instance = $this->builder->getObject();

        $this->assertInstanceOf('Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo', $instance);
        $this->assertEquals('42', $instance->getBar());
    }
}
