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
 * Month end function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MonthEndFunction extends BaseFunction
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('month_end', static function () {
            return DateFunctionUtil::getFormatter()->format(strtotime(date('Y-m-t 23:59:59')));
        });
    }
}
