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

use Klipper\Component\Model\Traits\LocaleableInterface;
use Klipper\Component\Security\Model\UserInterface;
use Klipper\Component\User\Choice\LocaleAvailable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class LocaleSubscriber implements EventSubscriberInterface
{
    protected TranslatorInterface $translator;

    protected bool $sessionEnabled = false;

    /**
     * @param TranslatorInterface $translator The translator instance
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Enable the locale session.
     */
    public function enableLocaleSession(): void
    {
        $this->sessionEnabled = true;
    }

    /**
     * Disable the locale session.
     */
    public function disableLocaleSession(): void
    {
        $this->sessionEnabled = false;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 16],
                ['onKernelRequestSession', 8],
            ],
            SecurityEvents::INTERACTIVE_LOGIN => [
                ['onInteractiveLogin', 0],
            ],
        ];
    }

    /**
     * Sets the first accept-language defined in the header of request.
     *
     * @param RequestEvent $event The event
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (null !== $event->getRequest()->attributes->get('_locale')) {
            return;
        }

        $locale = $event->getRequest()->getPreferredLanguage(LocaleAvailable::$availables);

        if (null !== $locale) {
            $event->getRequest()->setLocale($locale);
        }
    }

    /**
     * Sets the locale stocked in session.
     *
     * @param RequestEvent $event The event
     */
    public function onKernelRequestSession(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->sessionEnabled
                || null !== $request->attributes->get('_locale')
                || !$request->hasSession()
                || (null !== $request->getSession() && !$request->getSession()->isStarted())) {
            return;
        }

        /** @var Session $session */
        $session = $request->getSession();
        $locale = $session->get('_locale');

        if (null !== $locale) {
            $event->getRequest()->setLocale($locale);
            $session->set('_locale', $locale);

            if ($this->translator instanceof LocaleAwareInterface) {
                $this->translator->setLocale($locale);
            }
        }
    }

    /**
     * Sets the preferred locale of authenticated user.
     *
     * @param InteractiveLoginEvent $event The event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $request = $event->getRequest();
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserInterface && $user instanceof LocaleableInterface
                && null !== ($locale = $user->getLocale())) {
            \Locale::setDefault($locale);
            $event->getRequest()->setLocale($locale);

            if ($this->translator instanceof LocaleAwareInterface) {
                $this->translator->setLocale($locale);
            }

            if ($this->sessionEnabled && $request->hasSession()) {
                $session = $request->getSession();

                if ($session && $session->isStarted()) {
                    $session->set('_locale', $locale);
                }
            }
        }
    }
}
