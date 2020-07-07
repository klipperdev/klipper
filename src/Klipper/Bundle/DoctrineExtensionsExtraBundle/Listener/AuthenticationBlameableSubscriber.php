<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineExtensionsExtraBundle\Listener;

use Gedmo\Blameable\BlameableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class AuthenticationBlameableSubscriber implements EventSubscriberInterface
{
    private BlameableListener $blameableListener;

    public function __construct(BlameableListener $blameableListener)
    {
        $this->blameableListener = $blameableListener;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        ];
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $this->blameableListener->setUserValue($event->getAuthenticationToken()->getUser());
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $this->blameableListener->setUserValue($event->getAuthenticationToken()->getUser());
    }
}
