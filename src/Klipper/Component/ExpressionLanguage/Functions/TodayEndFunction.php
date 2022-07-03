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
 * Today end function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TodayEndFunction extends BaseFunction
{
    public function __construct(?\IntlDateFormatter $dateFormatter = null)
    {
        parent::__construct('today_end', static function () use ($dateFormatter) {
            $value = DateFunctionUtil::createDateTime('today', $dateFormatter);

            if (false !== $value) {
                $value->setTime(23, 59, 59);
            }

            return DateFunctionUtil::getFormatter($dateFormatter)->format($value);
        });
    }
}
