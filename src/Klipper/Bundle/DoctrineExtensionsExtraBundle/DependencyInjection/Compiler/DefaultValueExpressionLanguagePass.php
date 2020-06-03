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

use Klipper\Component\ExpressionLanguage\DependencyInjection\Compiler\AbstractExpressionLanguageProvidersPass;

/**
 * Register the expression language providers for default value.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DefaultValueExpressionLanguagePass extends AbstractExpressionLanguageProvidersPass
{
    protected function getExpressionLanguageId(): string
    {
        return 'klipper_doctrine_extensions_extra.default_value.expression_language';
    }

    protected function getProviderTagName(): string
    {
        return 'klipper_doctrine_extensions_extra.default_value.expression_language_provider';
    }

    protected function getFunctionTagName(): string
    {
        return 'klipper_doctrine_extensions_extra.default_value.expression_language_function';
    }
}
