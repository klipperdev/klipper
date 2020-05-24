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

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Persistence\ManagerRegistry;
use Klipper\Component\DoctrineExtra\Exception\ObjectManagerNotFoundException;
use Klipper\Component\DoctrineExtra\Exception\UnexpectedRepositoryException;

/**
 * Utils for doctrine repository.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RepositoryUtils
{
    /**
     * @param ManagerRegistry $doctrine        The doctrine registry
     * @param string          $class           The object class managed by doctrine
     * @param null|string     $repositoryClass The required repository class
     *
     * @throws ObjectManagerNotFoundException
     * @throws UnexpectedRepositoryException
     */
    public static function getRepository(ManagerRegistry $doctrine, string $class, ?string $repositoryClass = null): ObjectRepository
    {
        $om = ManagerUtils::getRequiredManager($doctrine, $class);
        $repository = $om->getRepository($class);

        if (null !== $repositoryClass && !$repository instanceof $repositoryClass) {
            throw UnexpectedRepositoryException::create($class, $repositoryClass);
        }

        return $repository;
    }
}
