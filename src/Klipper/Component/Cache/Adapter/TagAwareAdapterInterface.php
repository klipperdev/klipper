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

use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface as BaseTagAwareAdapterInterface;

/**
 * Tag Aware Cache Adapter Interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface TagAwareAdapterInterface extends AdapterInterface, BaseTagAwareAdapterInterface
{
}
