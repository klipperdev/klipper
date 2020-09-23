<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Security\Authorization\Expression;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Define some ExpressionLanguage functions.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class IsPortalProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('is_portal', static function () {
                return '$portal_context && $portal_context->isPortal()';
            }, static function (array $variables) {
                return isset($variables['portal_context']) && $variables['portal_context']->isPortal();
            }),
        ];
    }
}
