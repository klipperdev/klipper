<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\SessionBundle\Tests\DependencyInjection;

use Klipper\Bundle\SessionBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * Tests case for Configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ConfigurationTest extends TestCase
{
    public function testDefaultConfig(): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [[]]);

        static::assertEquals(
            array_merge([], self::getBundleDefaultConfig()),
            $config
        );
    }

    protected static function getBundleDefaultConfig(): array
    {
        return [
            'pdo' => [
                'enabled' => true,
                'dsn' => '%env(DATABASE_DRIVER)%:host=%env(DATABASE_HOST)%;dbname=%env(DATABASE_NAME)%',
                'db_options' => [
                    'db_username' => '%env(DATABASE_USER)%',
                    'db_password' => '%env(DATABASE_PASSWORD)%',
                    'db_connection_options' => [],
                ],
            ],
        ];
    }
}
