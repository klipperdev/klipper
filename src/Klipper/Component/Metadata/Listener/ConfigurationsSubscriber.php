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

use Klipper\Component\Metadata\MetadataManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ConfigurationsSubscriber implements EventSubscriberInterface
{
    /**
     * @var MetadataManagerInterface
     */
    private $metadataManager;

    /**
     * @param MetadataManagerInterface $metadataManager The metadata manager
     */
    public function __construct(MetadataManagerInterface $metadataManager)
    {
        $this->metadataManager = $metadataManager;
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
     * @param RequestEvent $event The event
     *
     * @throws
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $actionClass = $request->attributes->get('_action_class', '');

        if (!class_exists($actionClass) || !$this->metadataManager->has($actionClass)) {
            return;
        }

        $meta = $this->metadataManager->get($actionClass);
        $actionMeta = $meta->getAction($request->attributes->get('_action'));
        $configurations = [];

        foreach ($actionMeta->getConfigurations() as $config) {
            if (\is_object($config) && method_exists($config, 'getAliasName') && null !== $config->getAliasName()) {
                $alias = $config->getAliasName();

                if (method_exists($config, 'allowArray') && true === $config->allowArray()) {
                    $configurations['_'.$alias][] = $config;
                } elseif (!\array_key_exists('_'.$alias, $configurations)) {
                    $configurations['_'.$alias] = $config;
                } else {
                    throw new \LogicException(sprintf('Multiple "%s" annotations are not allowed.', $alias));
                }
            }
        }

        foreach ($configurations as $key => $attributes) {
            $request->attributes->set($key, $attributes);
        }
    }
}
