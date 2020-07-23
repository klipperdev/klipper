<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice\Repository\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;
use Klipper\Component\DoctrineChoice\Repository\ChoiceRepositoryInterface;
use Klipper\Component\DoctrineChoice\Util\ChoiceUtil;
use Klipper\Component\DoctrineExtensions\Util\SqlFilterUtil;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @method EntityManagerInterface getEntityManager()
 * @method QueryBuilder           createQueryBuilder($alias = 'o')
 */
trait ChoiceRepositoryTrait
{
    /**
     * @see ChoiceRepositoryInterface::findChoices()
     */
    public function findChoices(array $types): array
    {
        $filters = SqlFilterUtil::disableFilters($this->getEntityManager(), ['organizational']);
        /** @var ChoiceInterface[] $res */
        $res = $this->createQueryBuilder('c')
            ->where('c.type in (:types)')
            ->orderBy('c.type', 'ASC')
            ->addOrderBy('c.position', 'ASC')
            ->setParameter('types', $types)
            ->getQuery()
            ->getResult()
        ;
        SqlFilterUtil::enableFilters($this->getEntityManager(), $filters);

        return ChoiceUtil::getMapChoices($res);
    }
}
