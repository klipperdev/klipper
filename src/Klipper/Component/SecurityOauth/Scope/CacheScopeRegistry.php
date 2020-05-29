<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Scope;

use Klipper\Component\SecurityOauth\Scope\Loader\ScopeLoaderInterface;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Config\ConfigCacheFactoryInterface;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class CacheScopeRegistry implements ScopeRegistryInterface, WarmableInterface
{
    private ScopeRegistryInterface $scopeRegistry;

    private array $options = [
        'cache_dir' => null,
        'debug' => false,
    ];

    private ?ConfigCacheFactoryInterface $configCacheFactory = null;

    /**
     * @var string[]
     */
    private ?array $scopes = null;

    public function __construct(ScopeRegistryInterface $scopeRegistry, array $options = [])
    {
        $this->scopeRegistry = $scopeRegistry;
        $this->options = array_merge($this->options, $options);
    }

    public function addLoader(ScopeLoaderInterface $loader): self
    {
        $this->scopes = null;
        $this->scopeRegistry->addLoader($loader);

        return $this;
    }

    public function getScopes(): array
    {
        if (null === $this->options['cache_dir']) {
            return $this->scopeRegistry->getScopes();
        }

        if (null === $this->scopes) {
            $self = $this;
            $cache = $this->getConfigCacheFactory()->cache(
                $this->options['cache_dir'].'/scopes.php',
                static function (ConfigCacheInterface $cache) use ($self): void {
                    $loadedScopes = $self->scopeRegistry->getScopes();
                    $content = var_export($loadedScopes, true);
                    $cache->write($self->getContent($content), $self->getResources());
                }
            );

            $this->scopes = require $cache->getPath();
        }

        return $this->scopes;
    }

    public function getResources(): array
    {
        return $this->scopeRegistry->getResources();
    }

    public function warmUp($cacheDir): void
    {
        // skip warmUp when metadata scope loader doesn't use cache
        if (null === $this->options['cache_dir']) {
            return;
        }

        $this->scopes = null;
        $this->getScopes();
    }

    /**
     * Set the config cache factory.
     *
     * @param ConfigCacheFactoryInterface $configCacheFactory The config cache factory
     */
    public function setConfigCacheFactory(ConfigCacheFactoryInterface $configCacheFactory): void
    {
        $this->configCacheFactory = $configCacheFactory;
    }

    /**
     * Provides the ConfigCache factory implementation, falling back to a
     * default implementation if necessary.
     */
    private function getConfigCacheFactory(): ConfigCacheFactoryInterface
    {
        if (!$this->configCacheFactory) {
            $this->configCacheFactory = new ConfigCacheFactory($this->options['debug']);
        }

        return $this->configCacheFactory;
    }

    private function getContent(string $content): string
    {
        return sprintf(
            <<<'EOF'
                <?php

                return %s;

                EOF,
            $content
        );
    }
}
