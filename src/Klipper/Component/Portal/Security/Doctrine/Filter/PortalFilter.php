<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Security\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Klipper\Component\DoctrineExtensions\Filter\AbstractFilter;
use Klipper\Component\Object\Util\ClassUtil;
use Klipper\Component\Portal\Model\Traits\PortalableInterface;

/**
 * Doctrine Portal Filter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalFilter extends AbstractFilter
{
    /**
     * @throws
     */
    protected function supports(ClassMetadata $targetEntity): bool
    {
        return $this->hasParameter('portal_id')
            && $this->hasParameter('portal_user_id')
            && $this->isPortalableEntity($targetEntity)
        ;
    }

    /**
     * @throws
     */
    protected function doAddFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        $columnMapping = $targetEntity->getAssociationMapping('portal');
        $column = $columnMapping['joinColumns'][0]['name'];

        return "{$targetTableAlias}.{$column} = {$this->getParameter('portal_id')}";
    }

    /**
     * Check if the entity is a portalable entity.
     *
     * @param ClassMetadata $targetEntity The metadata of entity
     *
     * @throws
     */
    private function isPortalableEntity(ClassMetadata $targetEntity): bool
    {
        $ref = $targetEntity->reflClass;
        $hasAssociationPortal = isset($targetEntity->associationMappings['portal']['isOwningSide'])
            && $targetEntity->associationMappings['portal']['isOwningSide'];

        return $hasAssociationPortal
            || ClassUtil::isInstanceOf($ref, PortalableInterface::class);
    }
}
