<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Tests\Cache;

use Klipper\Component\Config\ConfigCollectionInterface;
use Klipper\Component\Config\Tests\Fixtures\Cache\MockCache;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\ConfigCacheFactoryInterface;
use Symfony\Component\Config\ConfigCacheInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class AbstractCacheTest extends TestCase
{
    protected ?string $cacheDir = null;

    protected function setUp(): void
    {
        $this->cacheDir = sys_get_temp_dir().'/klipper_config_cache_test';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->cacheDir)) {
            $this->removeDir($this->cacheDir);
        }
    }

    public function removeDir(string $dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            is_dir("{$dir}/{$file}") ? $this->removeDir("{$dir}/{$file}") : unlink("{$dir}/{$file}");
        }

        return rmdir($dir);
    }

    /**
     * @throws
     */
    public function testWarmUp(): void
    {
        /** @var ConfigCacheFactoryInterface|MockObject $configCacheFactory */
        $configCacheFactory = $this->getMockBuilder(ConfigCacheFactoryInterface::class)->getMock();
        $cacheLoader = new MockCache(static function (): void {}, []);

        static::assertNull($cacheLoader->getProtectedConfigCacheFactory());
        $cacheLoader->warmUp('cache_dir');
        static::assertNotNull($cacheLoader->getProtectedConfigCacheFactory());

        $cacheLoader->setConfigCacheFactory($configCacheFactory);
        static::assertSame($configCacheFactory, $cacheLoader->getProtectedConfigCacheFactory());
    }

    /**
     * @throws
     */
    public function testLoadConfigurationFromCache(): void
    {
        /** @var ConfigCacheFactoryInterface|MockObject $configCacheFactory */
        $configCacheFactory = $this->getMockBuilder(ConfigCacheFactoryInterface::class)->getMock();

        $factoryCollection = $this->getMockBuilder(ConfigCollectionInterface::class)->getMock();
        $factoryCollectionFile = $this->getContent(sprintf(
            'unserialize(%s)',
            var_export(serialize($factoryCollection), true)
        ));

        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir);
        }

        file_put_contents($this->cacheDir.'/cache_file.php', $factoryCollectionFile);

        $factory = static function () use ($factoryCollection) {
            return $factoryCollection;
        };

        $configCache = $this->getMockBuilder(ConfigCacheInterface::class)->getMock();
        $configCache->expects(static::once())->method('write');
        $configCache->expects(static::once())->method('getPath')->willReturn($this->cacheDir.'/cache_file.php');

        $configCacheFactory->expects(static::once())
            ->method('cache')
            ->willReturnCallback(function ($name, $callback) use ($configCache) {
                static::assertSame($this->cacheDir.'/prefix_configs.php', $name);
                $callback($configCache);

                return $configCache;
            })
        ;

        $cacheLoader = new MockCache($factory, [
            'cache_dir' => $this->cacheDir,
        ]);

        $cacheLoader->setConfigCacheFactory($configCacheFactory);

        $res = $cacheLoader->createConfigurations();
    }

    protected function getContent(string $content): string
    {
        return sprintf(
            <<<'EOF'
                <?php

                return %s;

                EOF
            ,
            $content
        );
    }
}
