<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\SmsSenderBundle\Tests\DependencyInjection;

use Klipper\Bundle\SmsSenderBundle\DependencyInjection\Configuration;
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
            'dsn' => 'sms://null',
        ];
    }
}
