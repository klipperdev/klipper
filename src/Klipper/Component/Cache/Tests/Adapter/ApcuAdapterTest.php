<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Cache\Tests\Adapter;

use Klipper\Component\Cache\Adapter\ApcuAdapter;

/**
 * Apcu Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ApcuAdapterTest extends AbstractAdapterTest
{
    protected function setUp(): void
    {
        if (!\function_exists('apcu_fetch') || !\ini_get('apc.enabled') || ('cli' === \PHP_SAPI && !\ini_get('apc.enable_cli'))) {
            static::markTestSkipped('APCu extension is required.');
        }

        $this->adapter = new ApcuAdapter(str_replace('\\', '.', __CLASS__).static::PREFIX_GLOBAL, 0);
        $this->adapter->clear();
    }
}
