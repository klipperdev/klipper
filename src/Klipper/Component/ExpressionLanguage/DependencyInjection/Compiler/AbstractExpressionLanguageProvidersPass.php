<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\ExpressionLanguage\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register the providers and functions for expression language service.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractExpressionLanguageProvidersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $id = $this->getExpressionLanguageId();

        if ($container->hasDefinition($id)) {
            $def = $container->getDefinition($id);

            foreach ($container->findTaggedServiceIds($this->getProviderTagName(), true) as $id => $attributes) {
                $def->addMethodCall('registerProvider', [new Reference($id)]);
            }

            foreach ($container->findTaggedServiceIds($this->getFunctionTagName(), true) as $id => $attributes) {
                $def->addMethodCall('addFunction', [new Reference($id)]);
            }
        }
    }

    /**
     * Get the service id of expression language.
     *
     * @return string
     */
    abstract protected function getExpressionLanguageId(): string;

    /**
     * Get the tag name for provider.
     *
     * @return string
     */
    abstract protected function getProviderTagName(): string;

    /**
     * Get the tag name for provider.
     *
     * @return string
     */
    abstract protected function getFunctionTagName(): string;
}
