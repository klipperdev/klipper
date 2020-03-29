<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\CacheBundle\Tests\DependencyInjection;

use Klipper\Bundle\CacheBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * Configuration Tests.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ConfigurationTest extends TestCase
{
    public function testNoConfig(): void
    {
        $config = [];
        $processor = new Processor();
        $configuration = new Configuration([], []);
        $res = $processor->processConfiguration($configuration, [$config]);

        $valid = [
            'override_cache_services' => [],
        ];

        $this->assertSame($valid, $res);
    }

    public function testOverrideCacheServicesConfig(): void
    {
        $config = [
            'override_cache_services' => [
                'cache.adapter.filesystem',
            ],
        ];
        $processor = new Processor();
        $configuration = new Configuration([], []);
        $res = $processor->processConfiguration($configuration, [$config]);

        $valid = [
            'override_cache_services' => [
                'cache.adapter.filesystem',
            ],
        ];

        $this->assertSame($valid, $res);
    }

    public function testOverrideCacheServicesConfigWithTrueValue(): void
    {
        $config = [
            'override_cache_services' => true,
        ];
        $processor = new Processor();
        $configuration = new Configuration([], []);
        $res = $processor->processConfiguration($configuration, [$config]);

        $valid = [
            'override_cache_services' => [],
        ];

        $this->assertSame($valid, $res);
    }

    public function testOverrideCacheServicesConfigWithFalseValue(): void
    {
        $config = [
            'override_cache_services' => false,
        ];
        $processor = new Processor();
        $configuration = new Configuration([], []);
        $res = $processor->processConfiguration($configuration, [$config]);

        $valid = [
            'override_cache_services' => [
                '_override_disabled',
            ],
        ];

        $this->assertSame($valid, $res);
    }
}
