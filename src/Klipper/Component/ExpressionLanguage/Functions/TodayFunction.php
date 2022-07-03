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
 * Today function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TodayFunction extends BaseFunction
{
    public function __construct(?\IntlDateFormatter $dateFormatter = null)
    {
        parent::__construct('today', static function () use ($dateFormatter) {
            return DateFunctionUtil::create('today', $dateFormatter);
        });
    }
}
