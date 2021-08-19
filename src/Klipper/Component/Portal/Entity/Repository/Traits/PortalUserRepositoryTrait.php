<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Entity\Repository\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Klipper\Component\DoctrineExtensions\Util\SqlFilterUtil;
use Klipper\Component\Portal\Entity\Repository\PortalUserRepositoryInterface;
use Klipper\Component\Portal\Model\PortalUserInterface;
use Klipper\Component\Portal\Model\Traits\PortalableInterface;
use Klipper\Component\Security\Model\Traits\OrganizationalInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Portal User repository class.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @see PortalUserRepositoryInterface
 *
 * @method QueryBuilder           createQueryBuilder(string $alias, $indexBy = null)
 * @method ClassMetadata          getClassMetadata()
 * @method EntityManagerInterface getEntityManager()
 */
trait PortalUserRepositoryTrait
{
    /**
     * @see PortalUserRepositoryInterface::findCurrentPortalUserByPortalName
     */
    public function findCurrentPortalUserByPortalName(string $portalName, ?UserInterface $user): ?PortalUserInterface
    {
        $userPortal = null;

        $em = $this->getEntityManager();
        $filters = SqlFilterUtil::findFilters($em, [], true);

        if ($user instanceof UserInterface) {
            if (is_a($this->getClassMetadata()->getReflectionClass()->getName(), OrganizationalInterface::class, true)) {
                $result = $this->createQueryBuilder('pu')
                    ->addSelect('p, u, o')
                    ->where('pu.user = :userId')
                    ->andWhere('pu.enabled = true')
                    ->andWhere('p.portalName = :portalName')
                    ->andWhere('p.portalEnabled = true')
                    ->leftJoin('pu.'.$this->getPortalAssociationName(), 'p')
                    ->leftJoin('pu.user', 'u')
                    ->leftJoin('pu.organization', 'o')
                    ->setParameter('userId', $user->getId())
                    ->setParameter('portalName', $portalName)
                    ->getQuery()
                    ->getResult()
                ;
            } else {
                $result = $this->createQueryBuilder('pu')
                    ->addSelect('p, u')
                    ->where('pu.user = :userId')
                    ->andWhere('pu.enabled = true')
                    ->andWhere('p.portalName = :portalName')
                    ->andWhere('p.portalEnabled = true')
                    ->leftJoin('pu.'.$this->getPortalAssociationName(), 'p')
                    ->leftJoin('pu.user', 'u')
                    ->setParameter('userId', $user->getId())
                    ->setParameter('portalName', $portalName)
                    ->getQuery()
                    ->getResult()
                ;
            }

            $userPortal = \count($result) > 0 ? $result[0] : null;
        }

        SqlFilterUtil::enableFilters($em, $filters);

        return $userPortal;
    }

    /**
     * @see PortalUserRepositoryInterface::findPortalUserById
     *
     * @param mixed $id
     */
    public function findPortalUserById($id): ?PortalUserInterface
    {
        $em = $this->getEntityManager();
        $filters = SqlFilterUtil::findFilters($em, [], true);
        SqlFilterUtil::disableFilters($em, $filters);

        $result = $this->createQueryBuilder('pu')
            ->addSelect('p, u, g')
            ->where('pu.id = :id')
            ->leftJoin('pu.'.$this->getPortalAssociationName(), 'p')
            ->leftJoin('pu.user', 'u')
            ->leftJoin('pu.groups', 'g')
            ->setMaxResults(1)
            ->setParameter('id', $id, \is_string($id) && !is_numeric($id) ? Types::GUID : null)
            ->getQuery()
            ->getResult()
        ;

        SqlFilterUtil::enableFilters($em, $filters);

        return \count($result) > 0 ? $result[0] : null;
    }

    protected function getPortalAssociationName(): string
    {
        $portalClass = $this->getClassMetadata()->getReflectionClass()->getName();

        return is_a($portalClass, PortalableInterface::class)
            ? $portalClass::getPortalAssociationName()
            : 'portal';
    }
}
