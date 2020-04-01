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

use Symfony\Component\Cache\Adapter\MemcachedAdapter as BaseMemcachedAdapter;

/**
 * Memcached Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MemcachedAdapter extends BaseMemcachedAdapter implements AdapterInterface
{
    use AdapterTrait;

    /**
     * {@inheritdoc}
     */
    protected function doClearByPrefix(string $namespace, string $prefix): bool
    {
        $ok = true;
        $client = $this->getParentClient();
        $version = AdapterUtil::getPropertyValue($this, 'namespaceVersion');

        foreach ($this->getAllItems($client) as $key) {
            $ok = !$this->doClearItem(urldecode($key), $namespace.$version.$prefix) && $ok ? false : $ok;
        }

        return $ok;
    }

    /**
     * Delete the key that starting by the prefix.
     *
     * @param string $id     The cache item id
     * @param string $prefix The full prefix
     *
     * @throws
     */
    private function doClearItem(string $id, string $prefix): bool
    {
        $key = substr($id, strrpos($id, ':') + 1);
        $res = true;

        if (!empty($key) && ('' === $prefix || 0 === strpos($id, $prefix))) {
            $res = $this->deleteItem($key);
        }

        return $res;
    }

    /**
     * Get all items.
     *
     * @param \Memcached $client The memcached client
     *
     * @return string[]
     */
    private function getAllItems(\Memcached $client): array
    {
        $res = $client->getAllKeys();

        return false !== $res ? $res : [];
    }

    /**
     * Get the client.
     */
    private function getParentClient(): \Memcached
    {
        $client = AdapterUtil::getPropertyValue($this, 'client');

        return $client ?? AdapterUtil::getPropertyValue($this, 'lazyClient');
    }
}
