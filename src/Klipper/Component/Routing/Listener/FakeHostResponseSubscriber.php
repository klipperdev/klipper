<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Routing\Listener;

use Klipper\Component\HttpFoundation\Util\RequestUtil;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class FakeHostResponseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', -90],
        ];
    }

    /**
     * Restore the fake host in redirect response.
     *
     * @param ResponseEvent $event The event
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if ($response instanceof RedirectResponse && $this->isFakeHost($response->getTargetUrl())) {
            $response->setTargetUrl(RequestUtil::restoreFakeHost($response->getTargetUrl()));
        } elseif (null !== $url = $response->headers->get('Location')) {
            if ($this->isFakeHost($url)) {
                $response->headers->set('Location', RequestUtil::restoreFakeHost($url));
            }
        }
    }

    /**
     * Check if the url has a fake host.
     *
     * @param string $url The url
     */
    protected function isFakeHost(string $url): bool
    {
        return !\in_array(parse_url($url, PHP_URL_HOST), [null, 'localhost'], true);
    }
}
