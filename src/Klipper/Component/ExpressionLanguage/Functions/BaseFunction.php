<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\ExpressionLanguage\Functions;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

/**
 * Base of expression function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class BaseFunction extends ExpressionFunction
{
    /**
     * @param string        $name      The name of expression function
     * @param null|callable $evaluator The evaluator function
     * @param null|callable $compiler  The compiler function
     */
    public function __construct(string $name, ?callable $evaluator = null, ?callable $compiler = null)
    {
        if (null === $evaluator) {
            $evaluator = static function () {
                return null;
            };
        }

        if (null === $compiler) {
            $compiler = static function (): void {
                throw new \InvalidArgumentException('The expression language function cannot be compiled');
            };
        }

        parent::__construct($name, $compiler, $evaluator);
    }
}
