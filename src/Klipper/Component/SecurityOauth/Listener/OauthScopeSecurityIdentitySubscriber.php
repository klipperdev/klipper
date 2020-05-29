<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Listener;

use Klipper\Component\Security\Event\AddSecurityIdentityEvent;
use Klipper\Component\Security\Identity\IdentityUtils;
use Klipper\Component\SecurityOauth\Identity\OauthScopeSecurityIdentity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthScopeSecurityIdentitySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            AddSecurityIdentityEvent::class => ['addScopeSecurityIdentities', 0],
        ];
    }

    /**
     * Add oauth scope security identities.
     *
     * @param AddSecurityIdentityEvent $event The event
     */
    public function addScopeSecurityIdentities(AddSecurityIdentityEvent $event): void
    {
        try {
            $sids = $event->getSecurityIdentities();
            $sids = IdentityUtils::merge(
                $sids,
                OauthScopeSecurityIdentity::fromToken($event->getToken())
            );
            $event->setSecurityIdentities($sids);
        } catch (\InvalidArgumentException $e) {
            // ignore
        }
    }
}
