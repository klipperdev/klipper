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

use Symfony\Component\Cache\Adapter\TraceableTagAwareAdapter as BaseTraceableTagAwareAdapter;

/**
 * Traceable Tag Aware Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TraceableTagAwareAdapter extends BaseTraceableTagAwareAdapter implements TagAwareAdapterInterface
{
    use TraceableTrait;
}
