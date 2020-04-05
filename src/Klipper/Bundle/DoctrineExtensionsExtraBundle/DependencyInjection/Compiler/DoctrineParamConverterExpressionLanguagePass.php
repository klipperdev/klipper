<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler;

use Klipper\Bundle\DoctrineExtensionsExtraBundle\Request\ParamConverter\DoctrineParamConverterExpressionLanguage;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Replace the expression language of doctrine param converter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DoctrineParamConverterExpressionLanguagePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('sensio_framework_extra.converter.doctrine.orm.expression_language.default')) {
            return;
        }

        $def = $container->getDefinition('sensio_framework_extra.converter.doctrine.orm.expression_language.default');
        $def->setClass(DoctrineParamConverterExpressionLanguage::class);
        $def->addArgument(new Reference('request_stack'));
    }
}
