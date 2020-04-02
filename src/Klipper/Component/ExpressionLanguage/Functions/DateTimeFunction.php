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
 * DateTime function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DateTimeFunction extends BaseFunction
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('datetime', static function ($arguments, $datetime = 'now') {
            return DateFunctionUtil::create($datetime);
        });
    }
}
