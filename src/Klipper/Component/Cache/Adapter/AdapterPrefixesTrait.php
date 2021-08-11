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

/**
 * Adapter Trait for clear by prefixes.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait AdapterPrefixesTrait
{
    public function clearByPrefix(string $prefix): bool
    {
        return $this->clearByPrefixes([$prefix]);
    }
}
