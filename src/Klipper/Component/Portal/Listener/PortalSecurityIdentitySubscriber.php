<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Listener;

use Klipper\Component\Portal\PortalContextInterface;
use Klipper\Component\Portal\Security\Identity\PortalSecurityIdentity;
use Klipper\Component\Security\Event\AddSecurityIdentityEvent;
use Klipper\Component\Security\Identity\CacheSecurityIdentityListenerInterface;
use Klipper\Component\Security\Identity\IdentityUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * Subscriber for add portal security identity.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalSecurityIdentitySubscriber implements EventSubscriberInterface, CacheSecurityIdentityListenerInterface
{
    private RoleHierarchyInterface $roleHierarchy;

    private PortalContextInterface $context;

    public function __construct(
        RoleHierarchyInterface $roleHierarchy,
        PortalContextInterface $context
    ) {
        $this->roleHierarchy = $roleHierarchy;
        $this->context = $context;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AddSecurityIdentityEvent::class => ['addPortalSecurityIdentities', 0],
        ];
    }

    public function getCacheId(): string
    {
        $portal = $this->context->getCurrentPortal();

        return null !== $portal
            ? 'portal'.$portal->getId()
            : '';
    }

    /**
     * Add portal security identities.
     *
     * @param AddSecurityIdentityEvent $event The event
     */
    public function addPortalSecurityIdentities(AddSecurityIdentityEvent $event): void
    {
        try {
            $sids = $event->getSecurityIdentities();
            $sids = IdentityUtils::merge(
                $sids,
                PortalSecurityIdentity::fromToken(
                    $event->getToken(),
                    $this->context,
                    $this->roleHierarchy
                )
            );
            $event->setSecurityIdentities($sids);
        } catch (\InvalidArgumentException $e) {
            // ignore
        }
    }
}
