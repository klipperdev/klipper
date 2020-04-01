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

use Klipper\Component\DefaultValue\ObjectConfigBuilder;
use Klipper\Component\DefaultValue\ObjectConfigBuilderInterface;
use Klipper\Component\DefaultValue\ResolvedObjectType;
use Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foobar;
use Klipper\Component\DefaultValue\Tests\Fixtures\Object\User;
use Klipper\Component\DefaultValue\Tests\Fixtures\Type\FooCompletType;
use Klipper\Component\DefaultValue\Tests\Fixtures\Type\FooType;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ObjectConfigBuilderTest extends TestCase
{
    /**
     * @var ObjectConfigBuilderInterface
     */
    protected $config;

    protected function setUp(): void
    {
        $options = [
            'username' => 'foo',
            'password' => 'bar',
        ];
        $rType = new ResolvedObjectType(new FooCompletType());

        $this->config = new ObjectConfigBuilder($options);
        $this->config->setType($rType);
    }

    protected function tearDown(): void
    {
        $this->config = null;
    }

    public function testGetObjectConfig(): void
    {
        $config = $this->config->getObjectConfig();

        static::assertEquals($this->config, $config);
    }

    public function testGetObjectConfigWithConfigLocked(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\BadMethodCallException::class);

        $this->config->getObjectConfig();

        $this->config->getObjectConfig();
    }

    public function testGetType(): void
    {
        $type = $this->config->getType();

        static::assertInstanceOf('Klipper\Component\DefaultValue\ResolvedObjectTypeInterface', $type);
        static::assertInstanceOf('Klipper\Component\DefaultValue\Tests\Fixtures\Type\FooCompletType', $type->getInnerType());
    }

    public function testSetType(): void
    {
        $type = $this->config->getType();

        static::assertInstanceOf('Klipper\Component\DefaultValue\ResolvedObjectTypeInterface', $type);
        static::assertInstanceOf('Klipper\Component\DefaultValue\Tests\Fixtures\Type\FooCompletType', $type->getInnerType());

        $rType = new ResolvedObjectType(new FooType());
        $config = $this->config->setType($rType);
        $type2 = $this->config->getType();

        static::assertInstanceOf('Klipper\Component\DefaultValue\ObjectConfigBuilderInterface', $config);
        static::assertInstanceOf('Klipper\Component\DefaultValue\ResolvedObjectTypeInterface', $type2);
        static::assertInstanceOf('Klipper\Component\DefaultValue\Tests\Fixtures\Type\FooType', $type2->getInnerType());
    }

    public function testSetTypeWithConfigLocked(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\BadMethodCallException::class);

        $rType = new ResolvedObjectType(new FooType());

        $this->config->getObjectConfig();
        $this->config->setType($rType);
    }

    public function testGetOptions(): void
    {
        $opts = $this->config->getOptions();

        static::assertIsArray($opts);
    }

    public function testHasAndGetOption(): void
    {
        static::assertTrue($this->config->hasOption('username'));
        static::assertEquals('foo', $this->config->getOption('username', 'default value'));

        static::assertTrue($this->config->hasOption('password'));
        static::assertEquals('bar', $this->config->getOption('password', 'default value'));

        static::assertFalse($this->config->hasOption('invalidProperty'));
        static::assertEquals('default value', $this->config->getOption('invalidProperty', 'default value'));
    }

    public function testSetInvalidData(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\InvalidArgumentException::class);

        $this->config->setData(42);
    }

    public function testSetValidData(): void
    {
        $data = new User('root', 'p@ssword');
        $config = $this->config->setData($data);

        static::assertEquals($this->config, $config);
        static::assertEquals($data, $this->config->getData());
        static::assertEquals(\get_class($data), $this->config->getDataClass());
    }

    public function testSetValidDataWithConfigLocked(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\BadMethodCallException::class);

        $data = new User('root', 'p@ssword');
        $this->config->setData($data);
        $this->config->getObjectConfig();

        $this->config->setData($data);
    }

    public function testGetProperties(): void
    {
        $data = new User('root', 'p@ssword');
        $this->config->setData($data);
        $properties = $this->config->getProperties();

        static::assertIsArray($properties);
        static::assertCount(9, $properties);
    }

    public function testGetProperty(): void
    {
        $data = new User('root', 'p@ssword');
        $this->config->setData($data);

        static::assertTrue($this->config->hasProperty('username'));
        static::assertTrue($this->config->hasProperty('password'));
        static::assertFalse($this->config->hasProperty('foobar'));

        static::assertEquals('root', $this->config->getProperty('username'));
        static::assertTrue($this->config->getProperty('enabled'));
        static::assertTrue($this->config->getProperty('bar'));
        static::assertFalse($this->config->getProperty('foo'));
    }

    public function testGetPropertyWithEmptyData(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\BadMethodCallException::class);

        static::assertNull($this->config->getData());
        static::assertNull($this->config->getDataClass());

        $this->config->getProperty('property');
    }

    public function testGetInvalidProperty(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\InvalidArgumentException::class);

        $data = new User('root', 'p@ssword');
        $this->config->setData($data);

        $this->config->getProperty('invalidField');
    }

    public function testSetProperties(): void
    {
        $data = new Foobar();
        $data->setBar('hello world');
        $data->setCustomField('42');
        $this->config->setData($data);

        static::assertEquals('hello world', $data->getBar());
        static::assertEquals('42', $data->getCustomField());
        static::assertFalse($this->config->getProperty('privateProperty'));

        $config = $this->config->setProperties([
            'bar' => 'value edited',
            'customField' => '21',
            'privateProperty' => true,
        ]);

        static::assertInstanceOf('Klipper\Component\DefaultValue\ObjectConfigBuilderInterface', $config);
        static::assertEquals('value edited', $data->getBar());
        static::assertEquals('21', $data->getCustomField());
        static::assertTrue($this->config->getProperty('privateProperty'));
    }

    public function testSetPropertiesWithConfigLocked(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\BadMethodCallException::class);

        $data = new Foobar();
        $this->config->setData($data);
        $this->config->getObjectConfig();

        $this->config->setProperties([
            'bar' => 'value edited',
        ]);
    }

    public function testSetPropertiesWithEmptyData(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\BadMethodCallException::class);

        static::assertNull($this->config->getData());
        static::assertNull($this->config->getDataClass());

        $this->config->setProperties([
            'property' => 'value',
        ]);
    }

    public function testSetPropertiesWithInvalidClassProperty(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\InvalidArgumentException::class);

        $data = new Foobar();
        $this->config->setData($data);

        $this->config->setProperties([
            'invalidProperty' => 'value',
        ]);
    }

    public function testSetProperty(): void
    {
        $data = new Foobar();
        $data->setBar('hello world');
        $this->config->setData($data);

        static::assertEquals('hello world', $data->getBar());

        $config = $this->config->setProperty('bar', 'value edited');

        static::assertInstanceOf('Klipper\Component\DefaultValue\ObjectConfigBuilderInterface', $config);
        static::assertEquals('value edited', $data->getBar());
    }

    public function testSetPropertyWithEmptyData(): void
    {
        $this->expectException(\Klipper\Component\DefaultValue\Exception\BadMethodCallException::class);

        static::assertNull($this->config->getData());
        static::assertNull($this->config->getDataClass());

        $this->config->setProperty('property', 'value');
    }
}
