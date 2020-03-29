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

use Klipper\Component\Config\ArrayResource;
use Klipper\Component\Config\ConfigCollectionInterface;
use Klipper\Component\Config\Loader\AbstractArrayResourceLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class AbstractArrayResourceLoaderTest extends TestCase
{
    /**
     * @throws
     */
    public function testSupports(): void
    {
        /** @var AbstractArrayResourceLoader|MockObject $loader */
        $loader = $this->getMockBuilder(AbstractArrayResourceLoader::class)
            ->setMethods(['createConfigCollection'])
            ->getMock()
        ;

        static::assertTrue($loader->supports(new ArrayResource()));
    }

    /**
     * @throws
     */
    public function testConstructor(): void
    {
        /** @var AbstractArrayResourceLoader|MockObject $loader */
        $loader = $this->getMockBuilder(AbstractArrayResourceLoader::class)
            ->setMethods(['createConfigCollection', 'import'])
            ->getMock()
        ;

        $collection = $this->getMockBuilder(ConfigCollectionInterface::class)->getMock();

        $loader->expects(static::once())
            ->method('createConfigCollection')
            ->willReturn($collection)
        ;

        $childCollection = $this->getMockBuilder(ConfigCollectionInterface::class)->getMock();

        $loader->expects(static::once())
            ->method('import')
            ->with('.', 'config')
            ->willReturn($childCollection)
        ;

        $collection->expects(static::once())
            ->method('addCollection')
            ->with($childCollection)
        ;

        $resource = new ArrayResource();
        $resource->add('.', 'config');

        $newCollection = $loader->load($resource);

        static::assertSame($collection, $newCollection);
    }
}
