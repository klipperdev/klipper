<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Model\Doctrine\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Klipper\Component\Model\Traits\LabelableInterface;
use Klipper\Component\Model\Traits\NameableInterface;

/**
 * Doctrine subscriber for the labelable trait.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class LabelableSubscriber implements EventSubscriber
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
        $em = $args->getEntityManager();
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
        if ($entity instanceof LabelableInterface
                && $entity instanceof NameableInterface
                && null === $entity->getLabel()) {
            $entity->setLabel($entity->getName());
            $uow->propertyChanged($entity, 'label', null, $entity->getName());
        }
    }
}
