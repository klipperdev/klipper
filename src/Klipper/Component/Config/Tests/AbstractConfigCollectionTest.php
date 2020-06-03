<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Tests;

use Klipper\Component\Config\AbstractConfigCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class AbstractConfigCollectionTest extends TestCase
{
    /**
     * @throws
     */
    public function testGetIterator(): void
    {
        /** @var AbstractConfigCollection $collection */
        $collection = $this->getMockForAbstractClass(AbstractConfigCollection::class);

        static::assertCount(0, $collection->getIterator());
    }

    /**
     * @throws
     */
    public function testAll(): void
    {
        /** @var AbstractConfigCollection $collection */
        $collection = $this->getMockForAbstractClass(AbstractConfigCollection::class);

        static::assertCount(0, $collection->all());
    }

    /**
     * @throws
     */
    public function testCount(): void
    {
        /** @var AbstractConfigCollection $collection */
        $collection = $this->getMockForAbstractClass(AbstractConfigCollection::class);

        static::assertSame(0, $collection->count());
    }

    /**
     * @throws
     */
    public function testAddResource(): void
    {
        /** @var AbstractConfigCollection $collection */
        $collection = $this->getMockForAbstractClass(AbstractConfigCollection::class);

        static::assertSame([], $collection->getResources());

        /** @var ResourceInterface $mockResource */
        $mockResource = $this->getMockBuilder(ResourceInterface::class)->getMock();

        $collection->addResource($mockResource);

        static::assertSame([$mockResource], $collection->getResources());
    }

    /**
     * @throws
     */
    public function testAddCollection(): void
    {
        /** @var AbstractConfigCollection $collection */
        $collection = $this->getMockForAbstractClass(AbstractConfigCollection::class);

        static::assertSame([], $collection->getResources());

        /** @var ResourceInterface $mockResource */
        $mockResource = $this->getMockBuilder(ResourceInterface::class)->getMock();

        /** @var AbstractConfigCollection $mockNewCollection */
        $mockNewCollection = $this->getMockForAbstractClass(AbstractConfigCollection::class);
        $mockNewCollection->addResource($mockResource);

        $collection->addCollection($mockNewCollection);

        static::assertSame([$mockResource], $collection->getResources());
    }

    /**
     * @throws
     */
    public function testClone(): void
    {
        $collection = $this->getMockForAbstractClass(AbstractConfigCollection::class);
        $configs = [
            'name' => new \stdClass(),
        ];

        $ref = new \ReflectionClass($collection);
        $propRef = $ref->getProperty('configs');
        $propRef->setAccessible(true);
        $propRef->setValue($collection, $configs);

        $newCollection = clone $collection;

        static::assertNotSame($collection, $newCollection);

        $ref = new \ReflectionClass($newCollection);
        $propRef = $ref->getProperty('configs');
        $propRef->setAccessible(true);

        static::assertNotSame($configs, $propRef->getValue($newCollection));
    }
}
