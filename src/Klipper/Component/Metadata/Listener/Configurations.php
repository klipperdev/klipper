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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Configurations implements ConfigurationsInterface
{
    private MetadataManagerInterface $metadataManager;

    /**
     * @param MetadataManagerInterface $metadataManager The metadata manager
     */
    public function __construct(MetadataManagerInterface $metadataManager)
    {
        $this->metadataManager = $metadataManager;
    }

    public function hasConfigurations(string $class): bool
    {
        return class_exists($class) && $this->metadataManager->has($class);
    }

    public function getConfigurations(string $class, string $action): array
    {
        $configurations = [];

        if ($this->hasConfigurations($class)) {
            $meta = $this->metadataManager->get($class);
            $actionMeta = $meta->getAction($action);

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
        }

        return $configurations;
    }
}
