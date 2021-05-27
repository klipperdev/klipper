<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ExportBundle\DependencyInjection\Compiler;

use Klipper\Component\Export\ViewTransformer\ExportViewTransformerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ExportViewTransformerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('klipper_export.manager')) {
            return;
        }

        $def = $container->getDefinition('klipper_export.manager');

        foreach ($this->findAndSortTaggedServices('klipper_export.view_transformer', $container) as $service) {
            $serviceDef = $container->getDefinition((string) $service);
            $serviceClass = (string) $serviceDef->getClass();

            if (is_a($serviceClass, ExportViewTransformerInterface::class, true)) {
                $def->addMethodCall('addViewTransformer', [$serviceDef]);
            }
        }
    }
}
