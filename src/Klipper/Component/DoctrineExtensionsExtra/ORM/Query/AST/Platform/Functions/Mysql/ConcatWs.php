<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtensionsExtra\ORM\Query\AST\Platform\Functions\Mysql;

use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\SqlWalker;
use Klipper\Component\DoctrineExtensionsExtra\ORM\Query\AST\Functions\String\ConcatWs as StringConcatWs;
use Klipper\Component\DoctrineExtensionsExtra\ORM\Query\AST\Platform\Functions\PlatformFunctionNode;

/**
 * Concat WS function for Mysql.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ConcatWs extends PlatformFunctionNode
{
    public function getSql(SqlWalker $sqlWalker): string
    {
        /** @var Node[] $values */
        $values = $this->parameters[StringConcatWs::VALUES_KEY];

        /** @var bool $notEmpty */
        $notEmpty = $this->parameters[StringConcatWs::NOT_EMPTY_KEY];

        $queryBuilder = ['CONCAT_WS('];

        for ($i = 0; $i < \count($values); ++$i) {
            if ($i > 0) {
                $queryBuilder[] = ', ';
            }

            $nodeSql = $sqlWalker->walkArithmeticPrimary($values[$i]);

            if ($notEmpty) {
                $nodeSql = sprintf("NULLIF(%s, '')", $nodeSql);
            }

            $queryBuilder[] = $nodeSql;
        }

        $queryBuilder[] = ')';

        return implode('', $queryBuilder);
    }
}
