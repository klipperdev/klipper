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

use Symfony\Component\Cache\Adapter\TagAwareAdapter as BaseTagAwareAdapter;

/**
 * Tag Aware Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TagAwareAdapter extends BaseTagAwareAdapter implements TagAwareAdapterInterface
{
    use AdapterDeferredTrait;
    use AdapterPrefixesTrait;

    public function clearByPrefixes(array $prefixes): bool
    {
        $itemsAdapter = AdapterUtil::getPropertyValue($this, 'pool');
        $this->clearDeferredByPrefixes($prefixes);

        return $itemsAdapter instanceof AdapterInterface
            ? $itemsAdapter->clearByPrefixes($prefixes)
            : $itemsAdapter->clear();
    }
}
