<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\User\Doctrine\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Klipper\Component\Resource\Domain\DomainManagerInterface;
use Klipper\Component\Security\Model\UserInterface;
use Klipper\Component\User\Model\Traits\ProfileableInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ProfileableSubscriber implements EventSubscriber
{
    private DomainManagerInterface $domainManager;

    private ValidatorInterface $validator;

    public function __construct(DomainManagerInterface $domainManager, ValidatorInterface $validator)
    {
        $this->domainManager = $domainManager;
        $this->validator = $validator;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof ProfileableInterface) {
            $entity->setInitial($this->getInitial($entity));
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->prePersist($args);
    }

    private function getInitial(ProfileableInterface $entity): string
    {
        $initial = null;
        $fn = $entity->getFirstName();
        $ln = $entity->getLastName();

        if (null !== $fn || null !== $ln) {
            $initial = null !== $fn && \strlen($fn) > 0 ? substr($fn, 0, 1) : '';
            $initial .= null !== $ln && \strlen($ln) > 0 ? substr($ln, 0, 1) : '';
        }

        if (null === $initial && $entity instanceof UserInterface) {
            $initial = substr($entity->getUserIdentifier(), 0, 1);
        }

        return strtoupper($initial);
    }
}
