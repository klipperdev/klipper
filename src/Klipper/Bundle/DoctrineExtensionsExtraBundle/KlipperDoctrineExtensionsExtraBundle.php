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

use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\DefaultValueExpressionLanguagePass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\DefaultValueLoadersPass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\DoctrineBlameablePass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\DoctrineParamConverterExpressionLanguagePass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\DoctrineTranslatablePass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\RequestQueryFilterableExpressionLanguagePass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\RequestQueryFilterableFormGuesserPass;
use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;
use Klipper\Component\ExpressionLanguage\DependencyInjection\Compiler\AbstractExpressionLanguageProvidersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperDoctrineExtensionsExtraBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
        $container->addCompilerPass(new DoctrineParamConverterExpressionLanguagePass());
        $container->addCompilerPass(new DefaultValueLoadersPass());
        $container->addCompilerPass(new DoctrineTranslatablePass());
        $container->addCompilerPass(new DoctrineBlameablePass());
        $container->addCompilerPass(new RequestQueryFilterableFormGuesserPass());

        if (class_exists(AbstractExpressionLanguageProvidersPass::class)) {
            $container->addCompilerPass(new RequestQueryFilterableExpressionLanguagePass());
            $container->addCompilerPass(new DefaultValueExpressionLanguagePass());
        }
    }
}
