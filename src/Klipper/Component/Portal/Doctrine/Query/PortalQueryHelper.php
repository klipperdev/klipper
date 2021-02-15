<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Doctrine\Query;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Klipper\Component\DoctrineExtensions\Util\SqlFilterUtil;
use Klipper\Component\DoctrineExtensionsExtra\Filterable\FilterableQueryInterface;
use Klipper\Component\DoctrineExtensionsExtra\Filterable\Parser\FilterRule;
use Klipper\Component\Portal\Model\Traits\PortalableInterface;
use Klipper\Component\Portal\PortalContextInterface;
use Klipper\Component\Security\Doctrine\DoctrineUtils;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalQueryHelper
{
    private FilterableQueryInterface $filterableQuery;

    private PortalContextInterface $portalContext;

    public function __construct(
        FilterableQueryInterface $filterableQuery,
        PortalContextInterface $portalContext
    ) {
        $this->filterableQuery = $filterableQuery;
        $this->portalContext = $portalContext;
    }

    /**
     * Gets the list of results for the query.
     *
     * Alias for execute(null, $hydrationMode = HYDRATE_OBJECT).
     *
     * @param int|string $hydrationMode
     *
     * @return mixed
     */
    public function getResult(Query $query, ?string $masterPath = null, $hydrationMode = Query::HYDRATE_OBJECT)
    {
        $res = $this->filterQuery($query, $masterPath, $filters)->getResult($hydrationMode);
        SqlFilterUtil::enableFilters($query->getEntityManager(), $filters);

        return $res;
    }

    /**
     * Gets the array of results for the query.
     *
     * Alias for execute(null, HYDRATE_ARRAY).
     */
    public function getArrayResult(Query $query, ?string $masterPath = null): array
    {
        $res = $this->filterQuery($query, $masterPath, $filters)->getArrayResult();
        SqlFilterUtil::enableFilters($query->getEntityManager(), $filters);

        return $res;
    }

    /**
     * Gets the scalar results for the query.
     *
     * Alias for execute(null, HYDRATE_SCALAR).
     */
    public function getScalarResult(Query $query, ?string $masterPath = null): array
    {
        $res = $this->filterQuery($query, $masterPath, $filters)->getScalarResult();
        SqlFilterUtil::enableFilters($query->getEntityManager(), $filters);

        return $res;
    }

    /**
     * Get exactly one result or null.
     *
     * @param int|string $hydrationMode
     *
     * @throws NonUniqueResultException
     *
     * @return mixed
     */
    public function getOneOrNullResult(Query $query, ?string $masterPath = null, $hydrationMode = null)
    {
        $res = $this->filterQuery($query, $masterPath, $filters)->getOneOrNullResult($hydrationMode);
        SqlFilterUtil::enableFilters($query->getEntityManager(), $filters);

        return $res;
    }

    /**
     * Gets the single result of the query.
     *
     * Enforces the presence as well as the uniqueness of the result.
     *
     * @param int|string $hydrationMode
     *
     * @throws NonUniqueResultException If the query result is not unique
     * @throws NoResultException        If the query returned no result and hydration mode is not HYDRATE_SINGLE_SCALAR
     *
     * @return mixed
     */
    public function getSingleResult(Query $query, ?string $masterPath = null, $hydrationMode = null)
    {
        $res = $this->filterQuery($query, $masterPath, $filters)->getSingleResult($hydrationMode);
        SqlFilterUtil::enableFilters($query->getEntityManager(), $filters);

        return $res;
    }

    /**
     * Gets the single scalar result of the query.
     *
     * Alias for getSingleResult(HYDRATE_SINGLE_SCALAR).
     *
     * @throws NoResultException        If the query returned no result
     * @throws NonUniqueResultException If the query result is not unique
     *
     * @return mixed the scalar result
     */
    public function getSingleScalarResult(Query $query, ?string $masterPath = null)
    {
        $res = $this->filterQuery($query, $masterPath, $filters)->getSingleScalarResult();
        SqlFilterUtil::enableFilters($query->getEntityManager(), $filters);

        return $res;
    }

    private function filterQuery(Query $query, ?string $masterPath = null, ?array &$disabledFilters = []): Query
    {
        if (null !== $masterPath) {
            $metadata = $this->getRootClassMetadata($query);

            if (!is_a($metadata->getName(), PortalableInterface::class, true)) {
                $disabledFilters = SqlFilterUtil::disableFilters($query->getEntityManager(), ['portal']);

                $this->filterableQuery->filter($query, FilterRule::create(
                    $masterPath,
                    'equal',
                    $this->getPortalId($query, $metadata)
                ));
            } else {
                $disabledFilters = [];
            }
        }

        return $query;
    }

    /**
     * @return null|int|string
     */
    private function getPortalId(Query $query, ?ClassMetadata $metadata = null)
    {
        return $this->portalContext->getCurrentPortalId()
            ?? DoctrineUtils::getMockZeroId($metadata ?? $this->getRootClassMetadata($query));
    }

    private function getRootClassMetadata(Query $query): ClassMetadata
    {
        /** @var Query\AST\IdentificationVariableDeclaration[] $varDeclarations */
        $varDeclarations = $query->getAST()->fromClause->identificationVariableDeclarations;

        foreach ($varDeclarations as $varDeclaration) {
            $rangeDeclaration = $varDeclaration->rangeVariableDeclaration;
            $class = $rangeDeclaration->abstractSchemaName;

            if ($rangeDeclaration->isRoot) {
                return $query->getEntityManager()->getClassMetadata($class);
            }
        }

        throw new \InvalidArgumentException('The root entity cannot be found on the current Doctrine query');
    }
}
