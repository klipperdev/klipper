<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Doctrine\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Klipper\Component\DoctrineExtensions\Util\SqlFilterUtil;
use Klipper\Component\Portal\Model\PortalUserInterface;
use Klipper\Component\Security\Model\OrganizationInterface;
use Klipper\Component\Security\Model\OrganizationUserInterface;
use Klipper\Component\Security\Model\UserInterface;
use Klipper\Component\Security\Organizational\OrganizationalContextInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OrganizationUserSubscriber implements EventSubscriber
{
    private OrganizationalContextInterface $orgContext;

    /**
     * @var int[]|string[]
     */
    private array $deletePortalUserIds = [];

    public function __construct(OrganizationalContextInterface $orgContext)
    {
        $this->orgContext = $orgContext;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
            Events::postFlush,
        ];
    }

    /**
     * @throws
     */
    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $metaFactory = $em->getMetadataFactory();
        $org = $this->orgContext->getCurrentOrganization();
        $orgClass = OrganizationUserInterface::class;

        if (!($metaFactory->hasMetadataFor($orgClass) || $metaFactory->isTransient($orgClass))
            || null === $this->orgContext->getCurrentOrganization()
        ) {
            return;
        }

        $this->findAndUpsertOrganizationUsers($em, $org);

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof OrganizationUserInterface && null !== $entity->getUser()) {
                $this->deletePortalUserIds[] = $entity->getUser()->getId();
            }
        }

        $this->deletePortalUserIds = array_unique($this->deletePortalUserIds);
    }

    /**
     * @throws
     */
    public function postFlush(PostFlushEventArgs $args): void
    {
        if (!empty($this->deletePortalUserIds)) {
            $em = $args->getEntityManager();

            $filters = SqlFilterUtil::disableFilters($em, [], true);
            $em->createQueryBuilder()
                ->delete()
                ->from(PortalUserInterface::class, 'pu')
                ->where('pu.user IN (:ids)')
                ->setParameter('ids', $this->deletePortalUserIds)
                ->getQuery()
                ->execute()
            ;
            SqlFilterUtil::enableFilters($em, $filters);
        }

        $this->deletePortalUserIds = [];
    }

    /**
     * @throws
     */
    private function findAndUpsertOrganizationUsers(EntityManagerInterface $em, OrganizationInterface $org): void
    {
        $uow = $em->getUnitOfWork();
        $orgUserMeta = $em->getMetadataFactory()->getMetadataFor(OrganizationUserInterface::class);
        /** @var UserInterface[] $users */
        $users = [];

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof PortalUserInterface) {
                $user = $entity->getUser();

                if ($user instanceof UserInterface) {
                    $users[$user->getId()] = $user;
                }
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof PortalUserInterface && (!method_exists($entity, 'isEnabled') || ($entity->isEnabled()))) {
                $user = $entity->getUser();

                if ($user instanceof UserInterface) {
                    $users[$user->getId()] = $user;
                }
            }
        }

        if (empty($users)) {
            return;
        }

        $filters = SqlFilterUtil::disableFilters($em, [], true);

        /** @var OrganizationUserInterface[] $orgUsersMap */
        $orgUsersMap = [];
        /** @var OrganizationUserInterface[] $orgUsers */
        $orgUsers = $em->createQueryBuilder()
            ->select('ou')
            ->from(OrganizationUserInterface::class, 'ou')
            ->where('ou.user IN(:users)')
            ->andWhere('ou.organization = :org')
            ->setParameter('users', array_values($users))
            ->setParameter('org', $org)
            ->getQuery()
            ->getResult()
        ;

        foreach ($orgUsers as $orgUser) {
            $orgUsersMap[$orgUser->getUser()->getId()] = $orgUser;
        }

        foreach ($users as $user) {
            if (isset($orgUsersMap[$user->getId()])) {
                $orgUser = $orgUsersMap[$user->getId()];

                if (method_exists($orgUser, 'isEnabled')
                    && method_exists($orgUser, 'setEnabled')
                    && !$orgUser->isEnabled()
                ) {
                    $orgUser->setEnabled(true);
                    $uow->recomputeSingleEntityChangeSet($orgUserMeta, $orgUser);
                }
            } else {
                /** @var OrganizationUserInterface $orgUser */
                $orgUser = $orgUserMeta->newInstance();
                $orgUser->setOrganization($org);
                $orgUser->setUser($user);

                if (method_exists($orgUser, 'setEnabled')) {
                    $orgUser->setEnabled(true);
                }

                $em->persist($orgUser);
                $uow->computeChangeSet($orgUserMeta, $orgUser);
            }
        }

        SqlFilterUtil::enableFilters($em, $filters);
    }
}
