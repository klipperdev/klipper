<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineMigrationsBundle\Tests\DependencyInjection;

use Klipper\Bundle\DoctrineMigrationsBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * Test case of configuration.
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
        $expected = [];

        $processor = new Processor();
        $configuration = new Configuration();

        $res = $processor->processConfiguration($configuration, [$config]);
        static::assertEquals($expected, $res);
    }

    public function testSchemaNamespaceFix(): void
    {
        $config = [
            'default' => [
                'schema_namespace_fix' => [
                    'namespace' => null,
                ],
            ],
            'test' => [
                'schema_namespace_fix' => [
                    'namespace' => 'public',
                ],
            ],
            'test2' => [
                'schema_namespace_fix' => 'public',
            ],
        ];
        $expected = [
            'default' => [
                'schema_namespace_fix' => [
                    'enabled' => true,
                    'namespace' => null,
                ],
            ],
            'test' => [
                'schema_namespace_fix' => [
                    'enabled' => true,
                    'namespace' => 'public',
                ],
            ],
            'test2' => [
                'schema_namespace_fix' => [
                    'enabled' => true,
                    'namespace' => 'public',
                ],
            ],
        ];

        $processor = new Processor();
        $configuration = new Configuration();

        $res = $processor->processConfiguration($configuration, [$config]);
        static::assertEquals($expected, $res);
    }
}
