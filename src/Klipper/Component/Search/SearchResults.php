<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Search;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SearchResults implements SearchResultsInterface
{
    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @var SearchResultInterface[]
     */
    protected $objects = [];

    /**
     * Constructor.
     *
     * @param SearchResultInterface[] $results The results
     */
    public function __construct(array $results)
    {
        foreach ($results as $result) {
            $this->objects[$result->getName()] = $result;
            $this->total += $result->getTotal();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectNames(): iterable
    {
        return array_keys($this->objects);
    }

    /**
     * {@inheritdoc}
     */
    public function getObjects(): iterable
    {
        return $this->objects;
    }

    /**
     * {@inheritdoc}
     */
    public function hasObject(string $name): bool
    {
        return isset($this->objects[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getObject(string $name): SearchResultInterface
    {
        return $this->objects[$name] ?? null;
    }
}
