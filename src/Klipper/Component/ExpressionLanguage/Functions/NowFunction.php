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
 * Now function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class NowFunction extends BaseFunction
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('now', static function () {
            return DateFunctionUtil::create('now');
        });
    }
}
