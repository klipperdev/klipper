<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Cache\Adapter;

use Symfony\Component\Cache\Adapter\TraceableAdapter as BaseTraceableAdapter;

/**
 * Traceable Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TraceableAdapter extends BaseTraceableAdapter implements AdapterInterface
{
    use TraceableTrait;
}
