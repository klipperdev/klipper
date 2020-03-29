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

use Symfony\Component\Cache\Adapter\PhpFilesAdapter as BasePhpFilesAdapter;

/**
 * Php Files Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PhpFilesAdapter extends BasePhpFilesAdapter implements AdapterInterface
{
    use AdapterPrefixesTrait;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes): bool
    {
        return $this->clear();
    }
}
