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
 * Date add function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DateAddFunction extends BaseFunction
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('date_add', static function ($arguments, $datetime = 'now', $diff = '1day') {
            $formatter = DateFunctionUtil::getFormatter();
            $value = DateFunctionUtil::createDateTime($datetime, $formatter);

            if ($value instanceof \DateTime) {
                $value->add(\DateInterval::createFromDateString($diff));
            }

            return $formatter->format($value);
        });
    }
}
