<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata;

use Klipper\Component\DoctrineExtra\Util\ClassUtils;
use Klipper\Component\Metadata\Loader\ChoiceNameCollection;
use Klipper\Component\Metadata\Loader\ObjectMetadataNameCollection;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Config\ConfigCacheFactoryInterface;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class CacheMetadataFactory implements MetadataFactoryInterface, WarmableInterface
{
    protected MetadataFactoryInterface $factory;

    /**
     * @var array
     */
    protected $options = [
        'cache_dir' => null,
        'debug' => false,
    ];

    private ?ConfigCacheFactoryInterface $configCacheFactory = null;

    /**
     * @var null|ObjectMetadataInterface[]
     */
    private ?array $metadatas = null;

    private ?ObjectMetadataNameCollection $names = null;

    /**
     * @var null|ChoiceInterface[]
     */
    private ?array $choices = null;

    private ?ChoiceNameCollection $choiceNames = null;

    /**
     * @param MetadataFactoryInterface $factory The metadata factory
     * @param array                    $options An array of options
     */
    public function __construct(MetadataFactoryInterface $factory, array $options = [])
    {
        $this->factory = $factory;
        $this->options = array_merge($this->options, $options);
    }

    public function getManagedClasses(): ObjectMetadataNameCollection
    {
        if (null === $this->options['cache_dir']) {
            return $this->factory->getManagedClasses();
        }

        if (null === $this->names) {
            $self = $this;
            $cache = $this->getConfigCacheFactory()->cache(
                $this->options['cache_dir'].'/names.php',
                static function (ConfigCacheInterface $cache) use ($self): void {
                    $classes = $self->factory->getManagedClasses();

                    $content = sprintf(
                        'unserialize(%s)',
                        var_export(serialize($classes), true)
                    );
                    $cache->write($self->getContent($content), $classes->getResources());
                }
            );

            $this->names = require $cache->getPath();
        }

        return $this->names;
    }

    public function isManagedClass(string $class): bool
    {
        return $this->factory->isManagedClass($class);
    }

    public function isManagedByName(string $name): bool
    {
        $names = $this->getManagedClasses()->all();

        return isset($names[$name]);
    }

    public function getManagedClass(string $class): string
    {
        return $this->factory->getManagedClass($class);
    }

    public function create(string $class): ObjectMetadataInterface
    {
        if (null === $this->options['cache_dir']) {
            return $this->factory->create($class);
        }

        $self = $this;
        $names = $this->getManagedClasses()->all();
        $class = ClassUtils::getRealClass($class);
        $class = $this->getManagedClass($names[$class] ?? $class);

        if (!isset($this->metadatas[$class])) {
            $filename = $this->options['cache_dir'].'/class_'.str_replace('\\', '_', $class).'.php';
            $cache = $this->getConfigCacheFactory()->cache(
                $filename,
                static function (ConfigCacheInterface $cache) use ($class, $self): void {
                    $metadata = $self->factory->create($class);
                    $content = sprintf(
                        'unserialize(%s, %s)',
                        var_export(serialize($metadata), true),
                        var_export([$metadata->getClass()], true)
                    );

                    $cache->write($self->getContent($content), $metadata->getResources());
                }
            );

            $this->metadatas[$class] = require $cache->getPath();
        }

        return $this->metadatas[$class];
    }

    public function createChoice(string $name): ChoiceInterface
    {
        if (null === $this->options['cache_dir']) {
            return $this->factory->createChoice($name);
        }

        $self = $this;

        if (!isset($this->choices[$name])) {
            $filename = $this->options['cache_dir'].'/choices_'.$name.'.php';
            $cache = $this->getConfigCacheFactory()->cache(
                $filename,
                static function (ConfigCacheInterface $cache) use ($name, $self): void {
                    $metadata = $self->factory->createChoice($name);
                    $content = sprintf(
                        'unserialize(%s, %s)',
                        var_export(serialize($metadata), true),
                        var_export([Choice::class], true)
                    );

                    $cache->write($self->getContent($content), $metadata->getResources());
                }
            );

            $this->choices[$name] = require $cache->getPath();
        }

        return $this->choices[$name];
    }

    public function getChoiceNames(): ChoiceNameCollection
    {
        if (null === $this->options['cache_dir']) {
            return $this->factory->getChoiceNames();
        }

        if (null === $this->choiceNames) {
            $self = $this;
            $cache = $this->getConfigCacheFactory()->cache(
                $this->options['cache_dir'].'/choice_names.php',
                static function (ConfigCacheInterface $cache) use ($self): void {
                    $choiceNames = $self->factory->getChoiceNames();
                    $content = sprintf(
                        'unserialize(%s)',
                        var_export(serialize($choiceNames), true)
                    );
                    $cache->write($self->getContent($content), $choiceNames->getResources());

                    foreach ($choiceNames as $choiceName) {
                        $self->createChoice($choiceName);
                    }
                }
            );

            $this->choiceNames = require $cache->getPath();
        }

        return $this->choiceNames;
    }

    public function isChoiceManaged(string $name): bool
    {
        $names = $this->getChoiceNames()->all();

        return \in_array($name, $names, true);
    }

    /**
     * @param mixed $cacheDir
     */
    public function warmUp($cacheDir): void
    {
        // skip warmUp when metadata manager doesn't use cache
        if (null === $this->options['cache_dir']) {
            return;
        }

        $this->metadatas = null;
        $this->names = null;
        $this->choices = null;

        foreach ($this->getManagedClasses()->all() as $class) {
            $this->create($class);
        }

        foreach ($this->getChoiceNames()->all() as $name) {
            $this->createChoice($name);
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
