<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class StreamWrapperSubscriber implements EventSubscriberInterface
{
    private $clients;

    /**
     * Constructor.
     *
     * @param object[] $clients
     */
    public function __construct(array $clients)
    {
        $this->clients = $clients;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 0],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        foreach ($this->clients as $client) {
            if (method_exists($client, 'registerStreamWrapper')) {
                $client->registerStreamWrapper();
            }
        }
    }
}
