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

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Klipper\Component\Portal\Entity\Repository\PortalUserRepositoryInterface;
use Klipper\Component\Portal\Model\PortalUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Portal User repository class.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @see PortalUserRepositoryInterface
 *
 * @method QueryBuilder  createQueryBuilder(string $alias)
 * @method ClassMetadata getClassMetadata()
 */
trait PortalUserRepositoryTrait
{
    /**
     * @see PortalUserRepositoryInterface::findCurrentPortalUserByPortalName
     */
    public function findCurrentPortalUserByPortalName(string $portalName, ?UserInterface $user): ?PortalUserInterface
    {
        $userPortal = null;

        if ($user instanceof UserInterface) {
            $result = $this->createQueryBuilder('up')
                ->addSelect('p, u')
                ->where('up.user = :userId')
                ->andWhere('p.name = :portalName')
                ->leftJoin('up.portal', 'p', Join::WITH, 'p.id = up.portal')
                ->leftJoin('up.user', 'u', Join::WITH, 'u.id = up.user')
                ->setParameter('userId', $user->getId())
                ->setParameter('portalName', $portalName)
                ->getQuery()
                ->getResult()
            ;

            $userPortal = \count($result) > 0 ? $result[0] : null;
        }

        return $userPortal;
    }
}
