<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ApiSearchBundle;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class RequestHeaders
{
    public const SEARCH_QUERY = 'X-Query';

    public const SEARCH_QUERY_FIELDS = 'X-Query-Fields';

    public const SEARCH_OBJECTS = 'X-Objects';

    public const SEARCH_OBJECT = 'X-Object';
}
