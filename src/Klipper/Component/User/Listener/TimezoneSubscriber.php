<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\User\Listener;

use Klipper\Component\Model\Traits\TimezoneableInterface;
use Klipper\Component\Security\Model\UserInterface;
use Klipper\Component\User\RequestHeaders;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TimezoneSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 11],
            ],
            SecurityEvents::INTERACTIVE_LOGIN => [
                ['onInteractiveLogin', 0],
            ],
        ];
    }

    /**
     * Sets the timezone.
     *
     * @param RequestEvent $event The event
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (null !== $request->attributes->get('_timezone')) {
            return;
        }

        if ($request->hasSession() && ($session = $request->getSession())->isStarted()) {
            $timezone = $session->get('_timezone');
        } else {
            $timezone = $request->headers->get(RequestHeaders::TIMEZONE);
        }

        if (!empty($timezone) && \in_array($timezone, \DateTimeZone::listIdentifiers(), false)) {
            $request->attributes->set('_timezone', $timezone);
            date_default_timezone_set($timezone);
        }
    }

    /**
     * Sets the preferred timezone of authenticated user.
     *
     * @param InteractiveLoginEvent $event The event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $request = $event->getRequest();
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserInterface && $user instanceof TimezoneableInterface
                && null !== ($timezone = $user->getTimezone())) {
            $request->attributes->set('_timezone', $timezone);
            date_default_timezone_set($timezone);

            if ($request->hasSession()) {
                $session = $request->getSession();

                if ($session && $session->isStarted()) {
                    $session->set('_timezone', $timezone);
                }
            }
        }
    }
}
