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
 * Month begin function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MonthBeginFunction extends BaseFunction
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('month_begin', static function () {
            return DateFunctionUtil::getFormatter()->format(strtotime(date('Y-m-01')));
        });
    }
}
