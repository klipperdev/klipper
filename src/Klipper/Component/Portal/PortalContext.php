<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal;

use Klipper\Component\Portal\Event\SetCurrentPortalEvent;
use Klipper\Component\Portal\Event\SetCurrentPortalUserEvent;
use Klipper\Component\Portal\Exception\RuntimeException;
use Klipper\Component\Portal\Model\PortalInterface;
use Klipper\Component\Portal\Model\PortalUserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Portal Context.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalContext implements PortalContextInterface
{
    protected TokenStorageInterface $tokenStorage;

    protected ?EventDispatcherInterface $dispatcher;

    protected ?PortalInterface $portal = null;

    protected ?PortalUserInterface $portalUser = null;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        ?EventDispatcherInterface $dispatcher = null
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->dispatcher = $dispatcher;
    }

    public function setCurrentPortal(?PortalInterface $portal): void
    {
        $this->getToken('portal', $portal instanceof PortalInterface);

        if ($this->portal !== $portal) {
            $old = $this->portal;
            $this->portal = $portal;
            $this->dispatch(
                SetCurrentPortalEvent::class,
                $portal,
                $old
            );
        }
    }

    public function getCurrentPortal(): ?PortalInterface
    {
        return $this->portal;
    }

    public function getCurrentPortalId()
    {
        return null !== $this->portal ? $this->portal->getId() : null;
    }

    public function setCurrentPortalUser(?PortalUserInterface $portalUser): void
    {
        $token = $this->getToken('portal user', $portalUser instanceof PortalUserInterface);
        $user = null !== $token ? $token->getUser() : null;
        $this->portalUser = null;
        $portal = null;

        if ($user instanceof UserInterface && $portalUser instanceof PortalUserInterface
                && $this->isSameUser($user, $portalUser)) {
            $old = $this->portalUser;
            $this->portalUser = $portalUser;
            $portal = $portalUser->getPortal();
            $this->dispatch(
                SetCurrentPortalUserEvent::class,
                $portalUser,
                $old
            );
        }

        $this->setCurrentPortal($portal);
    }

    public function getCurrentPortalUser(): ?PortalUserInterface
    {
        return $this->portalUser;
    }

    public function isPortal(): bool
    {
        return null !== $this->getCurrentPortal();
    }

    /**
     * @throws
     */
    protected function getToken(string $type, bool $tokenRequired = true): ?TokenInterface
    {
        $token = $this->tokenStorage->getToken();

        if ($tokenRequired && null === $token) {
            throw new RuntimeException(sprintf('The current %s cannot be added in security token because the security token is empty', $type));
        }

        return $token;
    }

    protected function dispatch(string $eventClass, ?object $subject, ?object $oldSubject): void
    {
        if (null !== $this->dispatcher && $oldSubject !== $subject) {
            $this->dispatcher->dispatch(new $eventClass($subject));
        }
    }

    private function isSameUser(UserInterface $user, PortalUserInterface $portalUser): bool
    {
        return null !== $portalUser->getUser()
            && $user->getUsername() === $portalUser->getUser()->getUsername();
    }
}
