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

use Klipper\Component\Intl\CalendarUtil;

/**
 * Week end function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class WeekEndFunction extends BaseFunction
{
    public function __construct()
    {
        parent::__construct('week_end', static function () {
            $value = date_create('this '.CalendarUtil::getLastDayOfWeekName());

            if (false !== $value) {
                $value->setTime(23, 59, 59);
            }

            return DateFunctionUtil::getFormatter()->format($value);
        });
    }
}
