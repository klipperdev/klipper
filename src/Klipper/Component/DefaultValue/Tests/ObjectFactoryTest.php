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

use Klipper\Component\DefaultValue\Extension\Core\Type\DefaultType;
use Klipper\Component\DefaultValue\ObjectFactory;
use Klipper\Component\DefaultValue\ObjectFactoryInterface;
use Klipper\Component\DefaultValue\ObjectRegistry;
use Klipper\Component\DefaultValue\PreloadedExtension;
use Klipper\Component\DefaultValue\ResolvedObjectTypeFactory;
use Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo;
use Klipper\Component\DefaultValue\Tests\Fixtures\Type\FooCompletType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ObjectFactoryTest extends TestCase
{
    /**
     * @var ObjectFactoryInterface
     */
    protected $factory;

    protected function setUp(): void
    {
        $exts = [
            new PreloadedExtension([
                'default' => new DefaultType(),
                'Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo' => new FooCompletType(),
            ], []),
        ];
        $registry = new ObjectRegistry($exts, new ResolvedObjectTypeFactory());

        $this->factory = new ObjectFactory($registry, new ResolvedObjectTypeFactory());
    }

    protected function tearDown(): void
    {
        $this->factory = null;
    }

    public function testCreateBuilderWithObjectTypeInstance(): void
    {
        $type = new FooCompletType();
        $type->configureOptions(new OptionsResolver());
        $builder = $this->factory->createBuilder($type, null, ['bar' => 'hello world']);

        static::assertInstanceOf('Klipper\Component\DefaultValue\ObjectConfigBuilderInterface', $builder);
        static::assertNull($builder->getData());

        $instance = $builder->getObject();
        static::assertInstanceOf('Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo', $instance);
        static::assertEquals('hello world', $instance->getBar());
    }

    public function testCreateBuilderWithObjectTypeInstanceWithSpecialValueOfBarField(): void
    {
        $type = new FooCompletType();
        $type->configureOptions(new OptionsResolver());
        $builder = $this->factory->createBuilder($type, null, ['bar' => 'the answer to life, the universe, and everything']);
        $instance = $builder->getObject();

        static::assertInstanceOf('Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo', $instance);
        static::assertEquals('42', $instance->getBar());
    }

    public function testCreateBuilderWithObjectTypeInstanceAndData(): void
    {
        $type = new FooCompletType();
        $type->configureOptions(new OptionsResolver());
        $data = new Foo();
        $builder = $this->factory->createBuilder($type, $data, ['bar' => 'hello world']);
        $instance = $builder->getObject();

        static::assertEquals($data, $instance);
        static::assertEquals('hello world', $instance->getBar());
    }

    public function testCreateBuilderWithObjectTypeInstanceAndDataWithValueInField(): void
    {
        $type = new FooCompletType();
        $type->configureOptions(new OptionsResolver());
        $data = new Foo();
        $data->setBar('has value');
        $builder = $this->factory->createBuilder($type, $data, ['bar' => 'hello world']);
        $instance = $builder->getObject();

        static::assertEquals($data, $instance);
        static::assertEquals('has value', $instance->getBar());
    }

    public function testCreateBuilderWithObjectTypeInstanceAndDataWithValueInFieldWithSpecialValueOfBarField(): void
    {
        $type = new FooCompletType();
        $type->configureOptions(new OptionsResolver());
        $data = new Foo();
        $data->setBar('the answer to life, the universe, and everything');
        $builder = $this->factory->createBuilder($type, $data, ['bar' => 'hello world']);
        $instance = $builder->getObject();

        static::assertEquals($data, $instance);
        static::assertEquals('42', $instance->getBar());
    }

    public function testCreateBuilderWithObjectTypeInstanceWithoutOptions(): void
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException::class);

        $type = new FooCompletType();
        $type->configureOptions(new OptionsResolver());

        $this->factory->createBuilder($type);
    }

    public function testCreateBuilderWithString(): void
    {
        $builder = $this->factory->createBuilder('Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo', null, ['bar' => 'hello world']);

        static::assertInstanceOf('Klipper\Component\DefaultValue\ObjectConfigBuilderInterface', $builder);
        static::assertNull($builder->getData());

        $instance = $builder->getObject();
        static::assertInstanceOf('Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo', $instance);
        static::assertEquals('hello world', $instance->getBar());
    }

    public function testCreateBuilderWithTypeIsNotAResolvedObjectTypeInstance(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\UnexpectedTypeException::class);

        $this->factory->createBuilder(42);
    }

    public function testCreateObject(): void
    {
        $instance = $this->factory->create('Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo', null, ['bar' => 'hello world']);

        static::assertInstanceOf('Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo', $instance);
        static::assertEquals('hello world', $instance->getBar());
    }

    public function testCreateObjectWithData(): void
    {
        $data = new Foo();
        $data->setBar('has value');
        $instance = $this->factory->create('Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo', $data, ['bar' => 'hello world']);

        static::assertEquals($data, $instance);
        static::assertEquals('has value', $instance->getBar());
    }

    public function testInjectDefaultValueInObject(): void
    {
        $data = new Foo();
        $instance = $this->factory->inject($data, ['bar' => 'hello world']);

        static::assertEquals($data, $instance);
        static::assertEquals('hello world', $instance->getBar());
    }

    public function testInjectDefaultValueInNonObject(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\UnexpectedTypeException::class);

        $this->factory->inject(42);
    }
}
