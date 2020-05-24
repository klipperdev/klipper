<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtra\Tests\Util;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Persistence\ManagerRegistry;
use Klipper\Component\DoctrineExtra\Util\RepositoryUtils;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class RepositoryUtilsTest extends TestCase
{
    public function testGetRepository(): void
    {
        $mockRepo = $this->getMockBuilder(ObjectRepository::class)->getMock();

        /** @var MockObject|ObjectManager $om */
        $om = $this->getMockBuilder(ObjectManager::class)->getMock();

        $om->expects(static::once())
            ->method('getRepository')
            ->with(\stdClass::class)
            ->willReturn($mockRepo)
        ;

        /** @var ManagerRegistry|MockObject $doctrine */
        $doctrine = $this->getMockBuilder(ManagerRegistry::class)->getMock();

        $doctrine->expects(static::once())
            ->method('getManagerForClass')
            ->with(\stdClass::class)
            ->willReturn($om)
        ;

        $repo = RepositoryUtils::getRepository($doctrine, \stdClass::class, \get_class($mockRepo));
        static::assertSame($mockRepo, $repo);
    }

    public function testGetRepositoryWithNoManager(): void
    {
        $this->expectException(\Klipper\Component\DoctrineExtra\Exception\ObjectManagerNotFoundException::class);
        $this->expectExceptionMessage('The doctrine manager for the "stdClass" class is not found');

        /** @var ManagerRegistry|MockObject $doctrine */
        $doctrine = $this->getMockBuilder(ManagerRegistry::class)->getMock();

        $doctrine->expects(static::once())
            ->method('getManagerForClass')
            ->with(\stdClass::class)
            ->willReturn(null)
        ;

        $doctrine->expects(static::once())
            ->method('getManagers')
            ->willReturn([])
        ;

        RepositoryUtils::getRepository($doctrine, \stdClass::class);
    }

    public function testGetRepositoryWithInvalidRepositoryClass(): void
    {
        $mockRepo = $this->getMockBuilder(ObjectRepository::class)->getMock();
        $expectedRepoClass = \get_class($mockRepo);

        $this->expectException(\Klipper\Component\DoctrineExtra\Exception\UnexpectedRepositoryException::class);
        $this->expectExceptionMessageRegExp('/The doctrine repository of the "stdClass" class is not an instance of the "(\w+)"/');

        /** @var MockObject|ObjectManager $om */
        $om = $this->getMockBuilder(ObjectManager::class)->getMock();

        $om->expects(static::once())
            ->method('getRepository')
            ->with(\stdClass::class)
            ->willReturn(new \stdClass())
        ;

        /** @var ManagerRegistry|MockObject $doctrine */
        $doctrine = $this->getMockBuilder(ManagerRegistry::class)->getMock();

        $doctrine->expects(static::once())
            ->method('getManagerForClass')
            ->with(\stdClass::class)
            ->willReturn($om)
        ;

        RepositoryUtils::getRepository($doctrine, \stdClass::class, $expectedRepoClass);
    }
}
