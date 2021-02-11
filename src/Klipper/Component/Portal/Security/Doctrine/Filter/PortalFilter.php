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
            && ClassUtil::isInstanceOf($targetEntity->reflClass, PortalableInterface::class)
        ;
    }

    /**
     * @throws
     */
    protected function doAddFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        $class = $targetEntity->getName();

        if (!is_a($class, PortalableInterface::class, true)) {
            return '';
        }

        $columnMapping = $targetEntity->getAssociationMapping($class::getPortalAssociationName());
        $column = $columnMapping['joinColumns'][0]['name'];

        return "{$targetTableAlias}.{$column} = {$this->getParameter('portal_id')}";
    }
}
