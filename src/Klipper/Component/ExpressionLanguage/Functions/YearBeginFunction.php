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

/**
 * Year begin function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class YearBeginFunction extends BaseFunction
{
    public function __construct()
    {
        parent::__construct('year_begin', static function () {
            return DateFunctionUtil::getFormatter()->format(strtotime(date('Y-01-01')));
        });
    }
}
