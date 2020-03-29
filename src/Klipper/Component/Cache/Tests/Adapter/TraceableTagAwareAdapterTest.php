<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Cache\Tests\Adapter;

use Klipper\Component\Cache\Adapter\TagAwareAdapterInterface;
use Klipper\Component\Cache\Adapter\TraceableTagAwareAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface as SymfonyTagAwareInterface;

/**
 * Traceable Tag Aware Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class TraceableTagAwareAdapterTest extends AbstractAdapterTest
{
    /**
     * @var MockObject|TagAwareAdapterInterface
     */
    protected $tagAwareAdapter;

    protected function setUp(): void
    {
        $this->tagAwareAdapter = $this->getTagAwareAdapter();
        $this->adapter = new TraceableTagAwareAdapter($this->tagAwareAdapter);
        $this->adapter->clear();
    }

    public function testClearByPrefix(): void
    {
        $res = $this->adapter->clearByPrefix(static::PREFIX_1);
        $this->assertTrue($res);
    }

    public function testClearByPrefixWithDeferredItem(): void
    {
        $res = $this->adapter->clearByPrefix(static::PREFIX_1);
        $this->assertTrue($res);
    }

    public function getAdapters(): array
    {
        /** @var MockObject|SymfonyTagAwareInterface $symfonyAdapter */
        $symfonyAdapter = $this->getMockBuilder(SymfonyTagAwareInterface::class)->getMock();
        $this->mockAdapter($symfonyAdapter);

        return [
            [new TraceableTagAwareAdapter($this->getTagAwareAdapter())],
            [new TraceableTagAwareAdapter($symfonyAdapter)],
        ];
    }

    /**
     * @dataProvider getAdapters
     *
     * @param TraceableTagAwareAdapter $adapter The adapter
     */
    public function testClearByPrefixWithDifferentAdapter(TraceableTagAwareAdapter $adapter): void
    {
        $res = $adapter->clearByPrefix(static::PREFIX_1);
        $this->assertTrue($res);
    }

    /**
     * @dataProvider getAdapters
     *
     * @param TraceableTagAwareAdapter $adapter The adapter
     */
    public function testClearByPrefixesWithDifferentAdapter(TraceableTagAwareAdapter $adapter): void
    {
        $res = $adapter->clearByPrefixes([static::PREFIX_1, static::PREFIX_2]);
        $this->assertTrue($res);
    }

    /**
     * @return MockObject|TagAwareAdapterInterface
     */
    private function getTagAwareAdapter()
    {
        $tagAwareAdapter = $this->getMockBuilder(TagAwareAdapterInterface::class)->getMock();
        $this->mockAdapter($tagAwareAdapter);

        $tagAwareAdapter->expects($this->any())
            ->method('clearByPrefix')
            ->willReturn(true)
        ;

        $tagAwareAdapter->expects($this->any())
            ->method('clearByPrefixes')
            ->willReturn(true)
        ;

        return $tagAwareAdapter;
    }

    /**
     * @param MockObject $adapter The mocked adapter
     */
    private function mockAdapter($adapter): void
    {
        $self = $this;
        $adapter->expects($this->any())
            ->method('getItem')
            ->willReturnCallback(function ($value) use ($self) {
                $item = $self->getMockBuilder(CacheItemInterface::class)->getMock();
                $item->expects($this->any())
                    ->method('getKey')
                    ->willReturn($value)
                ;

                return $item;
            })
        ;

        $adapter->expects($this->any())
            ->method('getItems')
            ->willReturnCallback(function ($values) use ($self) {
                $res = [];

                foreach ($values as $value) {
                    $item = $self->getMockBuilder(CacheItemInterface::class)->getMock();
                    $item->expects($this->any())
                        ->method('getKey')
                        ->willReturn($value)
                    ;

                    $res[] = $item;
                }

                return $res;
            })
        ;

        $adapter->expects($this->any())
            ->method('hasItem')
            ->willReturn(true)
        ;

        $adapter->expects($this->any())
            ->method('clear')
            ->willReturn(true)
        ;

        $adapter->expects($this->any())
            ->method('deleteItem')
            ->willReturn(true)
        ;

        $adapter->expects($this->any())
            ->method('deleteItems')
            ->willReturn(true)
        ;

        $adapter->expects($this->any())
            ->method('save')
            ->willReturn(true)
        ;

        $adapter->expects($this->any())
            ->method('saveDeferred')
            ->willReturn(true)
        ;

        $adapter->expects($this->any())
            ->method('commit')
            ->willReturn(true)
        ;
    }
}
