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
 * Year end function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class YearEndFunction extends BaseFunction
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('year_end', static function () {
            return DateFunctionUtil::getFormatter()->format(strtotime(date('Y-12-31 23:59:59')));
        });
    }
}
