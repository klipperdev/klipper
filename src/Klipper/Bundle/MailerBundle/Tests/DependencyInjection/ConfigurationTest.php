<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\MailerBundle\Tests\DependencyInjection;

use Klipper\Bundle\MailerBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * Tests for symfony extension configuration.
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

        static::assertEquals(array_merge([], self::getBundleDefaultConfig()), $config);
    }

    /**
     * @return array
     */
    protected static function getBundleDefaultConfig(): array
    {
        return [
            'sms_sender' => [
                'dsn' => 'sms://null',
            ],
            'twig' => [
                'sandbox' => [
                    'security_policy' => [
                        'override' => false,
                        'allowed_tags' => [],
                        'allowed_filters' => [],
                        'allowed_methods' => [],
                        'allowed_properties' => [],
                        'allowed_functions' => [],
                    ],
                    'available_namespaces' => [
                        'user_templates',
                    ],
                ],
                'loaders' => [
                    'doctrine' => false,
                ],
                'enable_unstrict_variables' => true,
                'default_locale' => null,
            ],
        ];
    }
}
