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

use Symfony\Component\Cache\Adapter\CouchbaseCollectionAdapter as BaseCouchbaseCollectionAdapter;

/**
 * Couchbase Collection Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class CouchbaseCollectionAdapter extends BaseCouchbaseCollectionAdapter implements AdapterInterface
{
    use AdapterTrait;

    protected function doClearByPrefix(string $namespace, string $prefix): bool
    {
        return $this->doClear($namespace.$prefix);
    }
}
