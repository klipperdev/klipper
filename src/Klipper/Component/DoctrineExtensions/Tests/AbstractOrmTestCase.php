<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtensions\Tests;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Cache\CacheConfiguration;
use Doctrine\ORM\Cache\CacheFactory;
use Doctrine\ORM\Cache\DefaultCacheFactory;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

/**
 * Tests case for orm.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
abstract class AbstractOrmTestCase extends TestCase
{
    protected bool $isSecondLevelCacheEnabled = false;

    protected ?CacheFactory $secondLevelCacheFactory = null;

    protected ?Cache $secondLevelCacheDriverImpl;
    /**
     * The metadata cache that is shared between all ORM tests (except functional tests).
     */
    private static ?Cache $_metadataCacheImpl = null;

    /**
     * The query cache that is shared between all ORM tests (except functional tests).
     */
    private static ?Cache $_queryCacheImpl = null;

    /**
     * Creates an EntityManager for testing purposes.
     *
     * NOTE: The created EntityManager will have its dependant DBAL parts completely
     * mocked out using a DriverMock, ConnectionMock, etc. These mocks can then
     * be configured in the tests to simulate the DBAL behavior that is desired
     * for a particular test,
     *
     * @param array|Connection $conn
     *
     * @throws
     */
    protected function _getTestEntityManager($conn = null, ?EventManager $eventManager = null, bool $withSharedMetadata = true): EntityManager
    {
        $metadataCache = $withSharedMetadata
            ? self::getSharedMetadataCacheImpl()
            : new ArrayCache();

        $config = new Configuration();

        $config->setMetadataCacheImpl($metadataCache);
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver([], true));
        $config->setQueryCacheImpl(self::getSharedQueryCacheImpl());
        $config->setProxyDir(__DIR__.'/Proxies');
        $config->setProxyNamespace('Klipper\Component\DoctrineExtensions\Tests\Proxies');
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(
            [
                realpath(__DIR__.'/Models'),
            ],
            true
        ));

        if ($this->isSecondLevelCacheEnabled) {
            $cacheConfig = new CacheConfiguration();
            $cache = $this->getSharedSecondLevelCacheDriverImpl();
            $factory = new DefaultCacheFactory($cacheConfig->getRegionsConfiguration(), $cache);

            $this->secondLevelCacheFactory = $factory;

            $cacheConfig->setCacheFactory($factory);
            $config->setSecondLevelCacheEnabled(true);
            $config->setSecondLevelCacheConfiguration($cacheConfig);
        }

        if (null === $conn) {
            $conn = [
                'driverClass' => Mocks\DriverMock::class,
                'wrapperClass' => Mocks\ConnectionMock::class,
                'user' => 'john',
                'password' => 'doe',
            ];
        }

        if (\is_array($conn)) {
            $conn = DriverManager::getConnection($conn, $config, $eventManager);
        }

        return Mocks\EntityManagerMock::create($conn, $config, $eventManager);
    }

    protected function getSharedSecondLevelCacheDriverImpl(): Cache
    {
        if (null === $this->secondLevelCacheDriverImpl) {
            $this->secondLevelCacheDriverImpl = new ArrayCache();
        }

        return $this->secondLevelCacheDriverImpl;
    }

    private static function getSharedMetadataCacheImpl(): Cache
    {
        if (null === self::$_metadataCacheImpl) {
            self::$_metadataCacheImpl = new ArrayCache();
        }

        return self::$_metadataCacheImpl;
    }

    private static function getSharedQueryCacheImpl(): Cache
    {
        if (null === self::$_queryCacheImpl) {
            self::$_queryCacheImpl = new ArrayCache();
        }

        return self::$_queryCacheImpl;
    }
}
