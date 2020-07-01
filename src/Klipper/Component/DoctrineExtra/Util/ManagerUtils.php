<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtra\Util;

use Doctrine\ORM\Mapping\ClassMetadata as OrmClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\Persistence\ObjectManager;
use Klipper\Component\DoctrineExtra\Exception\ObjectManagerNotFoundException;

/**
 * Utils for doctrine manager.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ManagerUtils
{
    /**
     * Get the doctrine object manager of the class.
     *
     * @param ManagerRegistry $or    The doctrine registry
     * @param string          $class The class name or doctrine shortcut class name
     */
    public static function getManager(ManagerRegistry $or, string $class): ?ObjectManager
    {
        $manager = $or->getManagerForClass($class);

        if (null === $manager) {
            foreach ($or->getManagers() as $objectManager) {
                try {
                    if (self::isValidManager($objectManager, $class)
                        && $objectManager->getMetadataFactory()->hasMetadataFor($class)) {
                        $manager = $objectManager;

                        break;
                    }
                } catch (MappingException $e) {
                    // skip class
                }
            }
        }

        return $manager;
    }

    /**
     * Get the required object manager.
     *
     * @param ManagerRegistry $or    The doctrine registry
     * @param string          $class The class name
     *
     * @throws ObjectManagerNotFoundException When the class is not registered in doctrine
     */
    public static function getRequiredManager(ManagerRegistry $or, string $class): ObjectManager
    {
        $manager = static::getManager($or, $class);

        if (null === $manager) {
            throw ObjectManagerNotFoundException::create($class);
        }

        return $manager;
    }

    /**
     * Check if the object manager is valid.
     *
     * @param ObjectManager $manager The object manager
     * @param string        $class   The class name
     */
    private static function isValidManager(ObjectManager $manager, string $class): bool
    {
        $meta = $manager->getClassMetadata($class);

        return !$meta instanceof OrmClassMetadata || !$meta->isMappedSuperclass;
    }
}
