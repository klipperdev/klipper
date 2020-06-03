<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\RoutingBundle\DependencyInjection\Compiler;

use Klipper\Component\Config\ConfigResource;
use Klipper\Component\Routing\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RouteMainResourcePass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        $def = $container->getDefinition('router.default');
        $mainResource = $def->getArgument(1);

        $def->replaceArgument(1, new Reference('klipper_routing.resource.array_resource'));
        $def->replaceArgument(2, array_merge($def->getArgument(2), [
            'resource_type' => 'array_resource',
        ]));

        // Add config resources in array resource
        $arrayConfigDef = $container->getDefinition('klipper_routing.resource.array_resource')
            ->addMethodCall('add', [$mainResource, \is_string($mainResource) ? 'service' : null])
        ;

        foreach ($this->findAndSortTaggedServices('klipper_routing.config', $container) as $config) {
            $configDef = $container->getDefinition((string) $config);

            if (!is_a($configDef->getClass(), ConfigResource::class, true)) {
                throw new InvalidArgumentException(sprintf(
                    'The route config resource must be an instance of %s',
                    ConfigResource::class
                ));
            }

            $arrayConfigDef->addMethodCall('add', [$config]);
        }
    }
}
