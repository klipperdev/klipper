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
 * Date diff function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DateDiffFunction extends BaseFunction
{
    public function __construct()
    {
        parent::__construct('date_diff', static function ($arguments, $datetime = 'now', $diff = '') {
            $formatter = DateFunctionUtil::getFormatter();
            $value = DateFunctionUtil::createDateTime($datetime, $formatter);

            if ($value instanceof \DateTime) {
                $value->add(\DateInterval::createFromDateString($diff));
            }

            return $formatter->format($value);
        });
    }
}
