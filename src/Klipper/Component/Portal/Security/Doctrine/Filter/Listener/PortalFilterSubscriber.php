<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Security\Doctrine\Filter\Listener;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Klipper\Component\DoctrineExtensionsExtra\Filter\Listener\AbstractFilterSubscriber;
use Klipper\Component\Portal\Model\PortalInterface;
use Klipper\Component\Portal\Model\PortalUserInterface;
use Klipper\Component\Portal\PortalContextInterface;
use Klipper\Component\Portal\Security\Doctrine\Filter\PortalFilter;
use Klipper\Component\Security\Doctrine\DoctrineUtils;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalFilterSubscriber extends AbstractFilterSubscriber
{
    private PortalContextInterface $portalContext;

    public function __construct(EntityManagerInterface $entityManager, PortalContextInterface $portalContext)
    {
        parent::__construct($entityManager);

        $this->portalContext = $portalContext;
    }

    protected function supports(): string
    {
        return PortalFilter::class;
    }

    protected function injectParameters(SQLFilter $filter): void
    {
        $portal = $this->portalContext->getCurrentPortal();
        $portalId = null !== $portal
            ? $portal->getId()
            : DoctrineUtils::getMockZeroId($this->entityManager->getClassMetadata(PortalInterface::class));
        $portalUser = $this->portalContext->getCurrentPortalUser();
        $portalUserId = null !== $portal
            ? $portalUser->getId()
            : DoctrineUtils::getMockZeroId($this->entityManager->getClassMetadata(PortalUserInterface::class));

        $filter->setParameter('portal_id', $portalId, \is_string($portalId) && !is_numeric($portalId) ? Types::GUID : null);
        $filter->setParameter('portal_user_id', $portalUserId, \is_string($portalUserId) && !is_numeric($portalUserId) ? Types::GUID : null);
    }
}
