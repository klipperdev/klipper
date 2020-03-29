<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Tests\Fixtures\Cache;

use Klipper\Component\Config\Cache\AbstractCache;
use Klipper\Component\Config\ConfigCollectionInterface;
use Symfony\Component\Config\ConfigCacheFactoryInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MockCache extends AbstractCache implements WarmableInterface
{
    /**
     * @var callable
     */
    private $factory;

    public function __construct(callable $factory, array $options = [])
    {
        parent::__construct($options);

        $this->factory = $factory;
    }

    public function warmUp($cacheDir): void
    {
        $this->getConfigCacheFactory();
    }

    public function getProtectedConfigCacheFactory(): ?ConfigCacheFactoryInterface
    {
        return $this->configCacheFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createConfigurations(): ConfigCollectionInterface
    {
        return $this->loadConfigurationFromCache('prefix', function () {
            $factory = $this->factory;

            return $factory();
        });
    }
}
