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
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use Klipper\Component\DoctrineExtensions\Filter\AbstractFilter;
use Klipper\Component\Portal\Model\Traits\PortalableInterface;
use Klipper\Component\Security\Doctrine\DoctrineUtils;

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
            && (!class_exists(AbstractPersonalTranslation::class)
                || (
                    class_exists(AbstractPersonalTranslation::class)
                    && !is_a($targetEntity->getName(), AbstractPersonalTranslation::class, true)
                ))
        ;
    }

    /**
     * @throws
     */
    protected function doAddFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        $class = $targetEntity->getName();

        if (!is_a($class, PortalableInterface::class, true)) {
            $mockId = DoctrineUtils::getMockZeroId($this->getEntityManager()->getClassMetadata($class));

            return "{$targetTableAlias}.id = {$mockId}";
        }

        $columnMapping = $targetEntity->getAssociationMapping($class::getPortalAssociationName());
        $column = $columnMapping['joinColumns'][0]['name'];

        return "{$targetTableAlias}.{$column} = {$this->getParameter('portal_id')}";
    }
}
