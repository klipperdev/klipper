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

use Symfony\Component\Cache\Adapter\AdapterInterface as BaseAdapterInterface;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter as BasePhpArrayAdapter;

/**
 * PHP Array Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PhpArrayAdapter extends BasePhpArrayAdapter implements AdapterInterface
{
    use AdapterPrefixesTrait;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes): bool
    {
        $this->initializeForPrefix();

        /** @var AdapterInterface|BaseAdapterInterface $fallbackPool */
        $fallbackPool = AdapterUtil::getPropertyValue($this, 'pool');

        return $fallbackPool instanceof AdapterInterface
            ? $this->clearItems($fallbackPool, $prefixes)
            : $this->clear();
    }

    /**
     * Load the cache file.
     */
    private function initializeForPrefix(): void
    {
        $keys = AdapterUtil::getPropertyValue($this, 'keys');
        $values = AdapterUtil::getPropertyValue($this, 'values');

        if (null === $values) {
            $file = AdapterUtil::getPropertyValue($this, 'file');
            $values = @(include $file) ?: [];

            AdapterUtil::setPropertyValue($this, 'keys', $keys);
            AdapterUtil::setPropertyValue($this, 'values', $values);
        }
    }

    /**
     * Clear the items.
     *
     * @param AdapterInterface $fallbackPool The fallback pool
     * @param string[]         $prefixes     The prefixes
     */
    private function clearItems(AdapterInterface $fallbackPool, array $prefixes): bool
    {
        $cleared = $fallbackPool->clearByPrefixes($prefixes);
        $keys = AdapterUtil::getPropertyValue($this, 'keys') ?: [];
        $values = AdapterUtil::getPropertyValue($this, 'values') ?: [];
        $warmValues = [];

        foreach ($keys as $key => $valuePrefix) {
            foreach ($prefixes as $prefix) {
                if ('' === $prefix || 0 !== strpos($key, $prefix)) {
                    $warmValues[$key] = $values[$valuePrefix];
                }
            }
        }

        if (\count($values) !== \count($warmValues)) {
            $this->warmUp($warmValues);
        }

        return $cleared;
    }
}
