<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtensionsExtra\Representation;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Klipper\Component\DoctrineExtensionsExtra\Pagination\RequestPaginationQuery;

/**
 * Representation of the pagination.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Pagination implements PaginationInterface
{
    protected int $page;

    protected int $limit;

    protected int $pages;

    protected int $total;

    /**
     * @var object[]
     */
    protected array $results;

    /**
     * @param object[] $results The results
     * @param null|int $page    The page number
     * @param null|int $limit   The limit of pagination
     * @param null|int $pages   The number of pages
     * @param null|int $total   The size of the collection
     */
    public function __construct(
        array $results,
        ?int $page = null,
        ?int $limit = null,
        ?int $pages = null,
        ?int $total = null
    ) {
        $this->page = \is_int($page) ? $page : 1;
        $this->limit = \is_int($limit) ? $limit : max(1, \count($results));
        $this->pages = \is_int($pages) ? $pages : 1;
        $this->total = \is_int($total) ? $total : \count($results);
        $this->results = $results;
    }

    public static function fromQuery(Query $query, bool $fetchJoinCollection = false, ?int $page = null): self
    {
        $paginator = new Paginator($query, $fetchJoinCollection);
        $total = $paginator->count();
        $results = $paginator->getIterator()->getArrayCopy();

        return new self(
            $results,
            $page ?? (int) $query->getHint(RequestPaginationQuery::HINT_PAGE_NUMBER),
            $query->getMaxResults(),
            (int) ceil($total / $query->getMaxResults()),
            $total
        );
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @param object[] $results
     */
    public function setResults(array $results): self
    {
        $this->results = $results;

        return $this;
    }
}
