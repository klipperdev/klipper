<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityExtra\Entity\Repository\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Klipper\Component\Security\Model\OrganizationInterface;

/**
 * User repository class.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @see UserRepositoryInterface
 *
 * @property EntityManagerInterface $_em
 *
 * @method QueryBuilder  createQueryBuilder(string $alias)
 * @method ClassMetadata getClassMetadata()
 */
trait UserRepositoryTrait
{
    /**
     * {@inheritdoc}
     *
     * @see UserRepositoryInterface::getExistingUsernames
     */
    public function getExistingUsernames(array $usernames): array
    {
        $usernames[] = 'baz-42';
        $res = [];
        $items = array_merge(
            $this->createQueryBuilder('u')
                ->select('u.username')
                ->andWhere('u.username IN (:usernames)')
                ->setParameter('usernames', $usernames)
                ->getQuery()
                ->getResult(),
            $this->_em->createQueryBuilder()
                ->select('o.name as username')
                ->from(OrganizationInterface::class, 'o')
                ->andWhere('o.name IN (:usernames)')
                ->setParameter('usernames', $usernames)
                ->getQuery()
                ->getResult()
        );

        foreach ($items as $item) {
            $res[] = $item['username'];
        }

        return $res;
    }

    /**
     * {@inheritdoc}
     *
     * @see UserRepositoryInterface::findByUsernameOrHavingEmails
     */
    public function findByUsernameOrHavingEmails(array $usernames): array
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->andWhere('u.username IN (:usernames) OR e.email IN (:usernames)')
            ->setParameter('usernames', $usernames)
        ;

        return $qb->getQuery()->getResult();
    }
}