<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Listener;

use Klipper\Component\Metadata\MetadataManagerInterface;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Config\ConfigCacheFactoryInterface;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class CacheConfigurations implements ConfigurationsInterface, WarmableInterface
{
    protected array $options = [
        'cache_dir' => null,
        'debug' => false,
    ];

    private MetadataManagerInterface $metadataManager;

    private Configurations $configurations;

    private ?ConfigCacheFactoryInterface $configCacheFactory = null;

    private ?array $configs = null;

    public function __construct(
        MetadataManagerInterface $metadataManager,
        Configurations $configurations,
        array $options = []
    ) {
        $this->metadataManager = $metadataManager;
        $this->configurations = $configurations;
        $this->options = array_merge($this->options, $options);
    }

    public function hasConfigurations(string $class): bool
    {
        return $this->configurations->hasConfigurations($class);
    }

    public function getConfigurations(string $class, string $action): array
    {
        if (null === $this->options['cache_dir']) {
            return $this->configurations->getConfigurations($class, $action);
        }

        if (!class_exists($class)) {
            return [];
        }

        if (!isset($this->configs[$class])) {
            $self = $this;

            $filename = $this->options['cache_dir'].'/configs_'.str_replace('\\', '_', $class).'.php';
            $cache = $this->getConfigCacheFactory()->cache(
                $filename,
                static function (ConfigCacheInterface $cache) use ($class, $action, $self): void {
                    $configurations = $self->configurations->getConfigurations($class, $action);
                    $content = sprintf(
                        'unserialize(%s, %s)',
                        var_export(serialize($configurations), true),
                        var_export([$class], true)
                    );

                    $cache->write($self->getContent($content), $self->metadataManager->get($class)->getResources());
                }
            );

            $this->configs[$class] = require $cache->getPath();
        }

        return $this->configs[$class];
    }

    public function warmUp(string $cacheDir): void
    {
        // Skip warmUp when metadata manager doesn't use cache
        if (null === $this->options['cache_dir']) {
            return;
        }

        $this->configs = null;

        foreach ($this->metadataManager->all() as $objectMeta) {
            $class = $objectMeta->getClass();

            foreach ($objectMeta->getActions() as $action) {
                $this->getConfigurations($class, $action->getName());
            }
        }
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

    /**
     * @param string $content The content
     */
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
