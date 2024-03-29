<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtensionsExtra\ORM\Query\AST;

use Doctrine\ORM\Query\QueryException;
use Klipper\Component\DoctrineExtensionsExtra\ORM\Query\AST\Platform\Functions\PlatformFunctionNode;

/**
 * Function factory.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class FunctionFactory
{
    /**
     * Create platform function node.
     *
     * @param string $platformName The platform name
     * @param string $functionName The function name
     * @param array  $parameters   The parameters
     *
     * @throws QueryException
     */
    public static function create(string $platformName, string $functionName, array $parameters): PlatformFunctionNode
    {
        $className = __NAMESPACE__
            .'\\Platform\\Functions\\'
            .self::classify(strtolower($platformName))
            .'\\'
            .self::classify(strtolower($functionName));

        if (!class_exists($className)) {
            throw QueryException::syntaxError(
                sprintf(
                    'Function "%s" does not supported for platform "%s"',
                    $functionName,
                    $platformName
                )
            );
        }

        return new $className($parameters);
    }

    private static function classify(string $word): string
    {
        return str_replace([' ', '_', '-'], '', ucwords($word, ' _-'));
    }
}
