<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Doctrine\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Klipper\Component\SecurityOauth\Model\OauthClientInterface;
use Klipper\Component\SecurityOauth\Util\Random;

/**
 * Doctrine subscriber for the oauth client.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthClientSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    /**
     * On flush action.
     */
    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->updateLabel($uow, $entity);
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $this->updateLabel($uow, $entity);
        }
    }

    private function updateLabel(UnitOfWork $uow, object $entity): void
    {
        if ($entity instanceof OauthClientInterface) {
            if (null === $entity->getSecret()) {
                $entity->setSecret(Random::generateToken());
                $uow->propertyChanged($entity, 'secret', null, $entity->getSecret());
            }

            if (null === $entity->getClientId()) {
                $entity->setClientId(Random::generateToken());
                $uow->propertyChanged($entity, 'clientId', null, $entity->getClientId());
            }
        }
    }
}
