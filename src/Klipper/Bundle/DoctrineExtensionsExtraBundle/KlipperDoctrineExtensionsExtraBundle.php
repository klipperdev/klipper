<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineExtensionsExtraBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\DefaultValueExpressionLanguagePass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\DefaultValueLoadersPass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\DoctrineBlameablePass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\DoctrineParamConverterExpressionLanguagePass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\DoctrineTranslatablePass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\RequestQueryFilterableExpressionLanguagePass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;
use Klipper\Component\ExpressionLanguage\DependencyInjection\Compiler\AbstractExpressionLanguageProvidersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperDoctrineExtensionsExtraBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
        $container->addCompilerPass(new DoctrineParamConverterExpressionLanguagePass());
        $container->addCompilerPass(new DefaultValueLoadersPass());
        $container->addCompilerPass(new DoctrineTranslatablePass());
        $container->addCompilerPass(new DoctrineBlameablePass());

        if (class_exists(AbstractExpressionLanguageProvidersPass::class)) {
            $container->addCompilerPass(new RequestQueryFilterableExpressionLanguagePass());
            $container->addCompilerPass(new DefaultValueExpressionLanguagePass());
        }

        $this->registerMappingsPass($container);
    }

    /**
     * Register the doctrine mapping.
     *
     * @param ContainerBuilder $container The container
     */
    private function registerMappingsPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createXmlMappingDriver(
                [
                    realpath(__DIR__.'/Resources/config/doctrine/model/auto_numberable') => 'Klipper\Component\DoctrineExtensionsExtra\AutoNumberable\Model',
                ]
            )
        );
    }
}
