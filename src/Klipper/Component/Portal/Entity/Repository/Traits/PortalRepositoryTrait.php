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

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Klipper\Component\Portal\Entity\Repository\PortalUserRepositoryInterface;

/**
 * Portal User repository class.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @see PortalUserRepositoryInterface
 *
 * @method EntityManager getEntityManager
 * @method string        getEntityName
 * @method QueryBuilder  createQueryBuilder(string $alias)
 * @method ClassMetadata getClassMetadata()
 */
trait PortalRepositoryTrait
{
    /**
     * Finds entities by a set of criteria with insensitive field.
     *
     * @return array The objects
     */
    public function findPortalByInsensitive(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $expr = $this->getEntityManager()->getExpressionBuilder();

        $qb->select('o')->from($this->getEntityName(), 'o');

        foreach ($criteria as $field => $value) {
            if (\in_array($field, $this->getPortalInsensitiveFields(), true)) {
                $qb->andWhere('LOWER(o.'.$field.') = :'.$field);
                $qb->setParameter($field, \is_string($value) ? mb_strtolower($value) : $value);

                continue;
            }

            if (null === $value) {
                $qb->andWhere($expr->isNull('o.'.$field));
            } else {
                $qb->andWhere('o.'.$field.' = :'.$field);
                $qb->setParameter($field, $value);
            }
        }

        if ($orderBy) {
            foreach ($orderBy as $field => $order) {
                $qb->addOrderBy('o.'.$field, $order);
            }
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Get the insensitive field names.
     *
     * @return string[]
     */
    protected function getPortalInsensitiveFields(): array
    {
        return [
            'portalName',
        ];
    }
}
