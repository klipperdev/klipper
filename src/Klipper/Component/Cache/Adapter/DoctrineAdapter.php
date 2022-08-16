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

use Symfony\Component\Cache\Adapter\DoctrineAdapter as BaseDoctrineAdapter;

/**
 * Doctrine Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @deprecated Since Symfony 5.4, use Doctrine\Common\Cache\Psr6\CacheAdapter instead
 */
class DoctrineAdapter extends BaseDoctrineAdapter implements AdapterInterface
{
    use AdapterPrefixesTrait;

    public function clearByPrefixes(array $prefixes): bool
    {
        return $this->clear();
    }
}
