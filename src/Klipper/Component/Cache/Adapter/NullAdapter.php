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

use Symfony\Component\Cache\Adapter\NullAdapter as BaseNullAdapter;

/**
 * Null Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class NullAdapter extends BaseNullAdapter implements AdapterInterface
{
    use AdapterPrefixesTrait;

    public function clearByPrefixes(array $prefixes): bool
    {
        return true;
    }
}
