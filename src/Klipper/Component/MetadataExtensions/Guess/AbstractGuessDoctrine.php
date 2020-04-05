<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Guess;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Klipper\Component\DoctrineExtra\Util\ManagerUtils;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractGuessDoctrine
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @var false[]|ObjectManager[]
     */
    protected $cacheManagers = [];

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry The doctrine registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Get the doctrine manager.
     *
     * @param string $class The class name
     */
    protected function getManager(string $class): ?ObjectManager
    {
        if (!\array_key_exists($class, $this->cacheManagers)) {
            $manager = ManagerUtils::getManager($this->registry, $class);
            $this->cacheManagers[$class] = false;

            if (null !== $manager) {
                $this->cacheManagers[$class] = $manager;
            }
        }

        return false !== $this->cacheManagers[$class] ? $this->cacheManagers[$class] : null;
    }

    /**
     * Get the doctrine class metadata.
     *
     * @param string $class The class name
     */
    protected function getClassMetadata(string $class): ?ClassMetadata
    {
        $manager = $this->getManager($class);

        return null !== $manager ? $manager->getClassMetadata($class) : null;
    }
}
