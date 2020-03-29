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

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ProxyAdapter as BaseProxyAdapter;

/**
 * Proxy Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ProxyAdapter extends BaseProxyAdapter implements AdapterInterface
{
    use AdapterPrefixesTrait;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes): bool
    {
        /** @var AdapterInterface|CacheItemPoolInterface $pool */
        $pool = AdapterUtil::getPropertyValue($this, 'pool');

        return $pool instanceof AdapterInterface
            ? $pool->clearByPrefixes($prefixes)
            : $pool->clear();
    }
}
