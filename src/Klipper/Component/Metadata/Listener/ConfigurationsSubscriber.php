<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ConfigurationsSubscriber implements EventSubscriberInterface
{
    private ConfigurationsInterface $configurations;

    public function __construct(ConfigurationsInterface $configurations)
    {
        $this->configurations = $configurations;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    /**
     * Modifies the Request object to apply configuration information found in
     * metadata actions.
     *
     * @throws
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $configurations = $this->configurations->getConfigurations(
            $request->attributes->get('_action_class', ''),
            $request->attributes->get('_action', '')
        );

        foreach ($configurations as $key => $attributes) {
            $request->attributes->set($key, $attributes);
        }
    }
}
