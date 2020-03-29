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

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata as OrmClassMetadata;
use Klipper\Component\DoctrineExtra\Util\ManagerUtils;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ManagerUtilsTest extends TestCase
{
    public function testGetManagerWithInvalidClass(): void
    {
        /** @var ClassMetadataFactory|MockObject $metaFactory */
        $metaFactory = $this->getMockBuilder(ClassMetadataFactory::class)->getMock();

        $metaFactory->expects(static::once())
            ->method('hasMetadataFor')
            ->with('InvalidClass')
            ->willReturn(false)
        ;

        /** @var MockObject|ObjectManager $manager */
        $manager = $this->getMockBuilder(ObjectManager::class)->getMock();
        $manager->expects(static::atLeastOnce())
            ->method('getMetadataFactory')
            ->willReturn($metaFactory)
        ;

        /** @var ManagerRegistry|MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();

        $registry->expects(static::once())
            ->method('getManagerForClass')
            ->with('InvalidClass')
            ->willReturn(null)
        ;

        $registry->expects(static::once())
            ->method('getManagers')
            ->willReturn([$manager])
        ;

        static::assertNull(ManagerUtils::getManager($registry, 'InvalidClass'));
    }

    public function testGetManagerWithValidClass(): void
    {
        /** @var ClassMetadataFactory|MockObject $metaFactory */
        $metaFactory = $this->getMockBuilder(ClassMetadataFactory::class)->getMock();

        $metaFactory->expects(static::once())
            ->method('hasMetadataFor')
            ->with('ValidClass')
            ->willReturn(true)
        ;

        /** @var MockObject|ObjectManager $manager */
        $manager = $this->getMockBuilder(ObjectManager::class)->getMock();
        $manager->expects(static::atLeastOnce())
            ->method('getMetadataFactory')
            ->willReturn($metaFactory)
        ;

        /** @var ManagerRegistry|MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();

        $registry->expects(static::once())
            ->method('getManagerForClass')
            ->with('ValidClass')
            ->willReturn(null)
        ;

        $registry->expects(static::once())
            ->method('getManagers')
            ->willReturn([$manager])
        ;

        /** @var ClassMetadata|MockObject $registry */
        $meta = $this->getMockBuilder(ClassMetadata::class)->getMock();

        $manager->expects(static::atLeastOnce())
            ->method('getClassMetadata')
            ->with('ValidClass')
            ->willReturn($meta)
        ;

        static::assertSame($manager, ManagerUtils::getManager($registry, 'ValidClass'));
    }

    public function testGetManagerWithValidClassButMappedSuperclass(): void
    {
        /** @var MockObject|ObjectManager $manager */
        $manager = $this->getMockBuilder(ObjectManager::class)->getMock();

        /** @var ManagerRegistry|MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();

        $registry->expects(static::once())
            ->method('getManagerForClass')
            ->with('ValidClass')
            ->willReturn(null)
        ;

        $registry->expects(static::once())
            ->method('getManagers')
            ->willReturn([$manager])
        ;

        /** @var MockObject|OrmClassMetadata $registry */
        $meta = $this->getMockBuilder(OrmClassMetadata::class)->disableOriginalConstructor()->getMock();
        $meta->isMappedSuperclass = true;

        $manager->expects(static::atLeastOnce())
            ->method('getClassMetadata')
            ->with('ValidClass')
            ->willReturn($meta)
        ;

        static::assertNull(ManagerUtils::getManager($registry, 'ValidClass'));
    }
}
