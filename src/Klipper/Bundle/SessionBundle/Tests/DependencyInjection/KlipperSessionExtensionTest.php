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

use Klipper\Bundle\SessionBundle\DependencyInjection\KlipperSessionExtension;
use Klipper\Bundle\SessionBundle\Exception\InvalidConfigurationException;
use Klipper\Bundle\SessionBundle\KlipperSessionBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Tests case for Extension.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class KlipperSessionExtensionTest extends TestCase
{
    protected function tearDown(): void
    {
        putenv('DATABASE_DRIVER');
        putenv('DATABASE_HOST');
        putenv('DATABASE_NAME');
        putenv('DATABASE_USER');
        putenv('DATABASE_PASSWORD');
    }

    public function testExtensionExist(): void
    {
        $container = $this->createContainer();

        static::assertTrue($container->hasExtension('klipper_session'));
    }

    public function testExtensionLoader(): void
    {
        $container = $this->createContainer();

        static::assertTrue($container->hasParameter('klipper_session.pdo.dsn'));
        static::assertTrue($container->hasParameter('klipper_session.pdo.db_options'));
        static::assertTrue($container->has('klipper_session.handler.pdo'));
    }

    public function testExtensionLoaderWithEnvVariables(): void
    {
        putenv('DATABASE_DRIVER=database_driver');
        putenv('DATABASE_HOST=database_host');
        putenv('DATABASE_NAME=database_name2');

        $container = $this->createContainer([
            'pdo' => [
                'dsn' => '%env(DATABASE_DRIVER)%:host=%env(DATABASE_HOST)%;dbname=%env(DATABASE_NAME)%',
            ],
        ]);

        static::assertTrue($container->hasParameter('klipper_session.pdo.dsn'));
        static::assertTrue($container->hasParameter('klipper_session.pdo.db_options'));
        static::assertTrue($container->has('klipper_session.handler.pdo'));

        $dsn = $container->getParameter('klipper_session.pdo.dsn');

        putenv('DATABASE_DRIVER=');
        putenv('DATABASE_HOST=');
        putenv('DATABASE_NAME=');

        static::assertSame('database_driver:host=database_host;dbname=database_name2', $dsn);
    }

    public function testExtensionLoaderWithEnvVariablesAndDatabaseUrl(): void
    {
        putenv('DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name');

        $container = $this->createContainer();

        static::assertTrue($container->hasParameter('klipper_session.pdo.dsn'));
        static::assertTrue($container->hasParameter('klipper_session.pdo.db_options'));
        static::assertTrue($container->has('klipper_session.handler.pdo'));

        $dsn = $container->getParameter('klipper_session.pdo.dsn');

        putenv('DATABASE_URL=');

        static::assertSame('mysql://db_user:db_password@127.0.0.1:3306/db_name', $dsn);
    }

    public function testExtensionLoaderWithEnvDatabaseUrlVariablesAndCustomDsn(): void
    {
        putenv('DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name');

        $container = $this->createContainer([
            'pdo' => [
                'dsn' => '%env(DATABASE_URL)%',
            ],
        ]);

        static::assertTrue($container->hasParameter('klipper_session.pdo.dsn'));
        static::assertTrue($container->hasParameter('klipper_session.pdo.db_options'));
        static::assertTrue($container->has('klipper_session.handler.pdo'));

        $dsn = $container->getParameter('klipper_session.pdo.dsn');

        putenv('DATABASE_URL=');

        static::assertSame('mysql://db_user:db_password@127.0.0.1:3306/db_name', $dsn);
    }

    public function testExtensionLoaderWithoutPdo(): void
    {
        $container = $this->createContainer(['pdo' => ['enabled' => false]]);

        static::assertFalse($container->hasParameter('klipper_session.pdo.dsn'));
        static::assertFalse($container->hasParameter('klipper_session.pdo.db_options'));
        static::assertFalse($container->has('klipper_session.handler.pdo'));
    }

    public function testExtensionDsnMissing(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->createContainer(['pdo' => ['dsn' => null]]);
    }

    /**
     * @throws
     */
    protected function createContainer(array $config = []): ContainerBuilder
    {
        $configs = empty($config) ? [] : [$config];
        $container = new ContainerBuilder();
        $container->setParameter('env(DATABASE_DRIVER)', 'pdo_database_driver');
        $container->setParameter('env(DATABASE_HOST)', 'database_host');
        $container->setParameter('env(DATABASE_NAME)', 'database_name');
        $container->setParameter('env(DATABASE_USER)', 'database_user');
        $container->setParameter('env(DATABASE_PASSWORD)', 'database_password');

        $bundle = new KlipperSessionBundle();
        $bundle->build($container);

        $extension = new KlipperSessionExtension();
        $container->registerExtension($extension);
        $extension->load($configs, $container);

        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->compile(true);

        return $container;
    }
}
