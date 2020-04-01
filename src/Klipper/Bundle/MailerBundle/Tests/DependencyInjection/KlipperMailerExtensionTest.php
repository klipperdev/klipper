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

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension;
use Klipper\Bundle\MailerBundle\DependencyInjection\KlipperMailerExtension;
use Klipper\Bundle\MailerBundle\KlipperMailerBundle;
use Klipper\Bundle\SmsSenderBundle\DependencyInjection\KlipperSmsSenderExtension;
use Klipper\Bundle\SmsSenderBundle\KlipperSmsSenderBundle;
use Klipper\Component\SmsSender\SmsSenderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Tests for symfony extension configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class KlipperMailerExtensionTest extends TestCase
{
    public function testExtensionExist(): void
    {
        $container = $this->createContainer();

        static::assertTrue($container->hasExtension('klipper_mailer'));
    }

    public function testExtensionLoader(): void
    {
        $container = $this->createContainer();

        static::assertTrue($container->hasDefinition('klipper_mailer.mailer'));
        static::assertTrue($container->hasDefinition('klipper_mailer.twig.loader.sandbox'));
        static::assertTrue($container->hasDefinition('klipper_mailer.twig.loader.filesystem_template'));
        static::assertTrue($container->hasDefinition('klipper_mailer.sandbox_templater'));
        static::assertTrue($container->hasDefinition('klipper_mailer.twig.extension.sandbox'));
        static::assertTrue($container->hasDefinition('klipper_mailer.twig.sandbox.security_policy'));
        static::assertTrue($container->hasDefinition('klipper_mailer.twig.symfony_mailer.sandbox_body_renderer'));
        static::assertTrue($container->hasDefinition('klipper_mailer.twig.symfony_mailer.unstrict_body_renderer'));
        static::assertTrue($container->hasDefinition('klipper_mailer.transporter.symfony_mailer_email'));
        static::assertFalse($container->hasDefinition('klipper_mailer.twig.loader.doctrine_template'));
    }

    public function testExtensionLoaderWithDoctrineEnabled(): void
    {
        $container = $this->createContainer([
            'klipper_mailer' => [
                'twig' => [
                    'loaders' => [
                        'doctrine' => true,
                    ],
                ],
            ],
        ]);

        static::assertTrue($container->hasDefinition('klipper_mailer.twig.loader.doctrine_template'));
    }

    public function testExtensionLoaderWithSmsSender(): void
    {
        $container = $this->createContainer([], [], [
            'klipper_sms_sender' => [
                'dsn' => 'sms://null',
            ],
        ]);

        static::assertTrue($container->hasDefinition('klipper_sms_sender.sender'));
        static::assertTrue($container->hasDefinition('klipper_sms_sender.default_transport'));
        static::assertTrue($container->hasDefinition('klipper_sms_sender.messenger.message_handler'));
        static::assertTrue($container->hasAlias('sms_sender'));
        static::assertTrue($container->hasAlias(SmsSenderInterface::class));

        static::assertTrue($container->hasDefinition('klipper_mailer.transporter.klipper_sms_sender'));

        static::assertTrue($container->hasDefinition('klipper_sms_sender.twig.message_listener'));
        static::assertTrue($container->hasDefinition('klipper_sms_sender.twig.mime_body_renderer'));
        static::assertTrue($container->hasDefinition('klipper_mailer.twig.klipper_sms_sender.sandbox_body_renderer'));
        static::assertTrue($container->hasDefinition('klipper_mailer.twig.klipper_sms_sender.unstrict_body_renderer'));
    }

    public function testExtensionLoaderWithUnstrictBodyRendererDisabled(): void
    {
        $container = $this->createContainer([
            'klipper_mailer' => [
                'twig' => [
                    'enable_unstrict_variables' => false,
                ],
            ],
        ]);

        static::assertTrue($container->hasDefinition('klipper_mailer.twig.symfony_mailer.sandbox_body_renderer'));
        static::assertFalse($container->hasDefinition('klipper_mailer.twig.symfony_mailer.unstrict_body_renderer'));
    }

    public function getFallbackLocales(): array
    {
        return [
            ['en', null, null],
            ['en', null, 'custom_fallback'],
            ['%locale_fallback%', null, 'locale_fallback'],
            ['%locale%', null, 'locale'],
            ['%default_locale%', null, 'default_locale'],
        ];
    }

    /**
     * @dataProvider getFallbackLocales
     */
    public function testExtensionLoaderWithCustomFallbackForFilesystemTemplate(
        string $expected,
        ?string $value,
        ?string $parameter = null
    ): void {
        \Locale::setDefault('fr_FR');
        $parameters = null === $parameter ? [] : [
            $parameter => $value,
        ];

        $container = $this->createContainer([
            'klipper_mailer' => [
                'twig' => [
                    'default_locale' => null === $parameter ? $value : null,
                ],
            ],
        ], $parameters);

        static::assertTrue($container->hasDefinition('klipper_mailer.twig.loader.filesystem_template'));
        $def = $container->getDefinition('klipper_mailer.twig.loader.filesystem_template');
        $defArgs = $def->getArguments();
        static::assertCount(2, $defArgs);
        static::assertSame($expected, $defArgs[1]);
    }

    protected function createContainer(array $configs = [], array $parameters = [], array $smsSenderConfigs = []): ContainerBuilder
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.bundles' => [
                'FrameworkBundle' => FrameworkBundle::class,
                'KlipperMailerBundle' => KlipperMailerBundle::class,
            ],
            'kernel.bundles_metadata' => [],
            'kernel.cache_dir' => sys_get_temp_dir().'/klipper_mailer_bundle',
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.project_dir' => sys_get_temp_dir().'/klipper_mailer_bundle',
            'kernel.root_dir' => sys_get_temp_dir().'/klipper_mailer_bundle/app',
            'kernel.charset' => 'UTF-8',
        ]));

        $container->getParameterBag()->add($parameters);

        $sfExt = new FrameworkExtension();
        $doctrineExt = new DoctrineExtension();
        $smsSenderExt = new KlipperSmsSenderExtension();
        $extension = new KlipperMailerExtension();

        $container->registerExtension($sfExt);
        $container->registerExtension($doctrineExt);
        $container->registerExtension($smsSenderExt);
        $container->registerExtension($extension);

        $sfExt->load([[]], $container);
        $doctrineExt->load([$this->getDoctrineConfig()], $container);
        $smsSenderExt->load($smsSenderConfigs, $container);
        $extension->load($configs, $container);

        $smsSenderBundle = new KlipperSmsSenderBundle();
        $bundle = new KlipperMailerBundle();

        $smsSenderBundle->build($container);
        $bundle->build($container);

        $optimizationPasses = [];

        foreach ($container->getCompilerPassConfig()->getOptimizationPasses() as $pass) {
            if (0 === strpos(\get_class($pass), 'Klipper\Bundle\MailerBundle\DependencyInjection\Compiler')) {
                $optimizationPasses[] = $pass;
            }
        }

        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);
        $container->compile();

        return $container;
    }

    protected function getDoctrineConfig(): array
    {
        return [
            'dbal' => [
                'default_connection' => 'default',
                'connections' => [
                    'default' => [
                        'driver' => 'pdo_sqlite',
                        'path' => '%kernel.cache_dir%/test.db',
                    ],
                ],
            ],
            'orm' => [
                'auto_generate_proxy_classes' => true,
                'entity_managers' => [
                    'default' => [
                        'auto_mapping' => true,
                    ],
                ],
            ],
        ];
    }
}
