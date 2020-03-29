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
 * Adapter Trait.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait AdapterTrait
{
    use AdapterPrefixesTrait;
    use AdapterDeferredTrait;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes): bool
    {
        $this->clearDeferredByPrefixes($prefixes);
        $namespace = AdapterUtil::getPropertyValue($this, 'namespace');
        $ok = true;

        foreach ($prefixes as $prefix) {
            $ok = $this->doClearByPrefix($namespace, $prefix) && $ok;
        }

        return $ok;
    }

    /**
     * Action to delete all items identified by the prefix in the pool.
     *
     * @param string $namespace The namespace
     * @param string $prefix    The prefix
     *
     * @return bool
     */
    abstract protected function doClearByPrefix(string $namespace, string $prefix): bool;
}
