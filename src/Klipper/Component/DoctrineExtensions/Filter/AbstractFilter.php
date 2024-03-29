<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtensions\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Base of Doctrine Filter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractFilter extends SQLFilter implements EnableFilterInterface
{
    private ?EntityManagerInterface $entityManager = null;

    private bool $enable = true;

    private ?\ReflectionProperty $refParameters = null;

    public function enable(): self
    {
        $this->enable = true;
        // Make sure the hash for the filter will be changed to invalidate the query cache (@see SQLFilter::__toString()).
        $this->setParameter(sprintf('disabled_%s', __CLASS__), false);

        return $this;
    }

    public function disable(): self
    {
        $this->enable = false;
        // Make sure the hash for the filter will be changed to invalidate the query cache (@see SQLFilter::__toString()).
        $this->setParameter(sprintf('disabled_%s', __CLASS__), true);

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enable;
    }

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        $enabled = $this->isEnabled();
        $supported = $this->supports($targetEntity);
        $class = $targetEntity->getName();
        $filter = $enabled && $supported ? $this->doAddFilterConstraint($targetEntity, $targetTableAlias) : '';

        // Make sure the hash for the filter will be changed to invalidate the query cache (@see SQLFilter::__toString()).
        $this->setParameter(sprintf('disabled_%s_%s', __CLASS__, $class), !$enabled);
        $this->setParameter(sprintf('supported_%s_%s', __CLASS__, $class), !$enabled);
        $this->setParameter(sprintf('sql_cache_%s_%s', __CLASS__, $class), $filter);

        return $filter;
    }

    /**
     * Gets the SQL query part to add to a query.
     *
     * The constraint SQL if there is available, empty string otherwise.
     *
     * @param ClassMetaData $targetEntity     The class metadata of target entity
     * @param string        $targetTableAlias The table alias of target entity
     */
    abstract protected function doAddFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string;

    /**
     * Check if the target entity is supported by the sql filter.
     *
     * @param ClassMetadata $targetEntity class metadata of target entity
     */
    protected function supports(ClassMetadata $targetEntity): bool
    {
        return true;
    }

    /**
     * Get the entity manager.
     *
     * @throws
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        if (null === $this->entityManager) {
            $ref = new \ReflectionProperty(SQLFilter::class, 'em');
            $ref->setAccessible(true);
            $this->entityManager = $ref->getValue($this);
        }

        return $this->entityManager;
    }

    /**
     * Get the class metadata.
     *
     * @param string $classname The class name
     */
    protected function getClassMetadata(string $classname): ClassMetadata
    {
        return $this->getEntityManager()->getClassMetadata($classname);
    }

    /**
     * Gets a parameter to use in a query without the output escaping.
     *
     * @param string $name The name of the parameter
     *
     * @return null|bool|bool[]|float|float[]|int|int[]|string|string[]
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    protected function getRealParameter(string $name)
    {
        $this->getParameter($name);

        if (null === $this->refParameters) {
            $this->refParameters = new \ReflectionProperty(SQLFilter::class, 'parameters');
            $this->refParameters->setAccessible(true);
        }

        $parameters = $this->refParameters->getValue($this);

        return $parameters[$name]['value'];
    }
}
