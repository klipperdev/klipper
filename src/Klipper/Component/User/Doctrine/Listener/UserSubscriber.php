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
use Klipper\Component\DoctrineExtensionsExtra\Util\ListenerUtil;
use Klipper\Component\Resource\Domain\DomainManagerInterface;
use Klipper\Component\Security\Model\UserInterface;
use Klipper\Component\User\Model\ProfileInterface;
use Klipper\Component\User\Model\Traits\ProfileableInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UserSubscriber implements EventSubscriber
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
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof UserInterface) {
            $this->createProfile($entity);
        }
    }

    /**
     * Create the profile of user.
     *
     * @param UserInterface $entity The user entity
     */
    protected function createProfile(UserInterface $entity): void
    {
        if ($entity instanceof ProfileableInterface && null === $entity->getProfile()) {
            /** @var ProfileInterface $profile */
            $profile = $this->domainManager->get(ProfileInterface::class)->newInstance();
            $profile->setUser($entity);

            ListenerUtil::validateEntity($this->validator, $profile);
            $entity->setProfile($profile);
        }
    }
}
