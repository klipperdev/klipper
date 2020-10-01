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
use Klipper\Component\Metadata\Guess\GuessActionConfigInterface;
use Klipper\Component\Metadata\Guess\GuessAssociationConfigInterface;
use Klipper\Component\Metadata\Guess\GuessConfigInterface;
use Klipper\Component\Metadata\Guess\GuessFieldConfigInterface;
use Klipper\Component\Metadata\Guess\GuessObjectConfigInterface;
use Klipper\Component\Metadata\Guess\GuessRegistryAwareInterface;
use Klipper\Component\Metadata\Loader\ChoiceNameCollection;
use Klipper\Component\Metadata\Loader\MetadataCompleteLoaderInterface;
use Klipper\Component\Metadata\Loader\MetadataDynamicLoaderInterface;
use Klipper\Component\Metadata\Loader\MetadataLoaderInterface;
use Klipper\Component\Metadata\Loader\ObjectMetadataNameCollection;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MetadataRegistry implements MetadataRegistryInterface
{
    /**
     * @var ObjectMetadataBuilderInterface[]
     */
    protected array $builders = [];

    protected array $dynamicLoadBuilders = [];

    /**
     * @var MetadataCompleteLoaderInterface[]
     */
    protected array $completeLoaders = [];

    /**
     * @var MetadataDynamicLoaderInterface[]
     */
    protected array $dynamicLoaders = [];

    /**
     * @var GuessConfigInterface[]
     */
    protected array $guesserConfigs = [];

    protected ObjectMetadataNameCollection $names;

    /**
     * @var ChoiceBuilderInterface[]
     */
    protected array $choices = [];

    protected ChoiceNameCollection $choiceNames;

    protected array $resolveTargets;

    protected bool $init = false;

    /**
     * @param ObjectMetadataBuilderInterface[] $builders       The object metadata builders
     * @param MetadataLoaderInterface[]        $loaders        The metadata loaders
     * @param GuessConfigInterface[]           $guessConfigs   The metadata guess configs
     * @param string[]                         $resolveTargets The map of resolve targets
     */
    public function __construct(array $builders = [], array $loaders = [], array $guessConfigs = [], array $resolveTargets = [])
    {
        $this->names = new ObjectMetadataNameCollection();
        $this->choiceNames = new ChoiceNameCollection();
        $this->resolveTargets = $resolveTargets;

        foreach ($loaders as $loader) {
            $this->addLoader($loader);
        }

        foreach ($builders as $builder) {
            $this->addBuilder($builder);
        }

        foreach ($guessConfigs as $guessConfig) {
            $this->addGuessConfig($guessConfig);
        }
    }

    public function addLoader(MetadataLoaderInterface $loader): self
    {
        if ($loader instanceof MetadataCompleteLoaderInterface) {
            $this->completeLoaders[] = $loader;
        } elseif ($loader instanceof MetadataDynamicLoaderInterface) {
            $this->dynamicLoaders[] = $loader;
        }

        return $this;
    }

    public function addGuessConfig(GuessConfigInterface $guessConfig): self
    {
        if ($guessConfig instanceof GuessObjectConfigInterface) {
            $this->guesserConfigs[GuessObjectConfigInterface::class][] = $guessConfig;
        }

        if ($guessConfig instanceof GuessActionConfigInterface) {
            $this->guesserConfigs[GuessActionConfigInterface::class][] = $guessConfig;
        }

        if ($guessConfig instanceof GuessFieldConfigInterface) {
            $this->guesserConfigs[GuessFieldConfigInterface::class][] = $guessConfig;
        }

        if ($guessConfig instanceof GuessAssociationConfigInterface) {
            $this->guesserConfigs[GuessAssociationConfigInterface::class][] = $guessConfig;
        }

        if ($guessConfig instanceof GuessRegistryAwareInterface) {
            $guessConfig->setRegistry($this);
        }

        return $this;
    }

    public function addBuilder(ObjectMetadataBuilderInterface $metadata): self
    {
        $class = $metadata->getClass();
        $this->builders[$class] = $metadata;
        $this->dynamicLoadBuilders[$class] = true;

        return $this;
    }

    public function getBuilder(string $class): ?ObjectMetadataBuilderInterface
    {
        $this->init();
        $realClass = $this->findClassName(ClassUtils::getRealClass($class));
        $builder = null;
        $hasBuilder = \array_key_exists($realClass, $this->builders);

        if (!$hasBuilder || \array_key_exists($realClass, $this->dynamicLoadBuilders)) {
            if (!$hasBuilder) {
                $this->builders[$realClass] = false;
            }

            unset($this->dynamicLoadBuilders[$realClass]);

            foreach ($this->dynamicLoaders as $loader) {
                $builder = $loader->loadBuilder($realClass);

                if (null !== $builder) {
                    $this->mergeBuilder($builder);
                }
            }

            if (null !== $builder) {
                $this->guessConfig($builder);
            }
        }

        if (false !== $this->builders[$realClass]) {
            $builder = $this->builders[$realClass];
            $this->names->add($realClass, $builder->getName());

            foreach ($builder->getResources() as $resource) {
                $this->names->addResource($resource);
            }
        }

        return $builder;
    }

    /**
     * Guess the configuration of metadata builder.
     *
     * @param ObjectMetadataBuilderInterface $builder The object metadata builder
     */
    public function guessConfig(ObjectMetadataBuilderInterface $builder): ObjectMetadataBuilderInterface
    {
        if (\count($this->guesserConfigs[GuessObjectConfigInterface::class] ?? []) > 0) {
            foreach ($this->guesserConfigs[GuessObjectConfigInterface::class] as $guessConfig) {
                if ($guessConfig instanceof GuessObjectConfigInterface) {
                    $guessConfig->guessObjectConfig($builder);
                }
            }
        }

        if (\count($this->guesserConfigs[GuessFieldConfigInterface::class] ?? []) > 0) {
            foreach ($builder->getFields() as $fieldBuilder) {
                foreach ($this->guesserConfigs[GuessFieldConfigInterface::class] as $guessConfig) {
                    if ($guessConfig instanceof GuessFieldConfigInterface) {
                        $guessConfig->guessFieldConfig($fieldBuilder);
                    }
                }
            }
        }

        if (\count($this->guesserConfigs[GuessAssociationConfigInterface::class] ?? []) > 0) {
            foreach ($builder->getAssociations() as $assoBuilder) {
                foreach ($this->guesserConfigs[GuessAssociationConfigInterface::class] as $guessConfig) {
                    if ($guessConfig instanceof GuessAssociationConfigInterface) {
                        $guessConfig->guessAssociationConfig($assoBuilder);
                    }
                }
            }
        }

        if (\count($this->guesserConfigs[GuessActionConfigInterface::class] ?? []) > 0) {
            $actions = $builder->getActions() ?? [];

            foreach ($actions as $actionBuilder) {
                foreach ($this->guesserConfigs[GuessActionConfigInterface::class] as $guessConfig) {
                    if ($guessConfig instanceof GuessActionConfigInterface) {
                        $guessConfig->guessActionConfig($actionBuilder);
                    }
                }
            }
        }

        return $builder;
    }

    public function getNames(): ObjectMetadataNameCollection
    {
        $this->init();

        return $this->names;
    }

    public function addChoice(ChoiceBuilderInterface $choiceBuilder): self
    {
        $name = $choiceBuilder->getName();

        if (isset($this->choices[$name])) {
            $this->choices[$name]->merge($choiceBuilder);
        } else {
            $this->choices[$name] = $choiceBuilder;
            $this->choiceNames->add($name);
        }

        foreach ($choiceBuilder->getResources() as $resource) {
            $this->choiceNames->addResource($resource);
        }

        return $this;
    }

    public function getChoice(string $name): ?ChoiceBuilderInterface
    {
        return $this->choices[$name] ?? null;
    }

    public function getChoiceNames(): ChoiceNameCollection
    {
        return $this->choiceNames;
    }

    /**
     * Init the registry.
     */
    protected function init(): void
    {
        if (!$this->init) {
            $this->init = true;

            foreach ($this->completeLoaders as $loader) {
                $this->mergeBuilders($loader->load());
            }

            foreach ($this->dynamicLoaders as $loader) {
                $this->names->addCollection($loader->loadNames());
            }
        }
    }

    /**
     * Merge the metadata builders.
     *
     * @param ObjectMetadataBuilderInterface[] $builders The object metadata builders
     */
    protected function mergeBuilders(array $builders): void
    {
        foreach ($builders as $builder) {
            $this->mergeBuilder($builder);
        }
    }

    /**
     * Merge the metadata builder.
     *
     * @param ObjectMetadataBuilderInterface $builder The metadata builder
     */
    protected function mergeBuilder(ObjectMetadataBuilderInterface $builder): void
    {
        $class = $builder->getClass();

        if (isset($this->builders[$class]) && false !== $this->builders[$class]) {
            $this->builders[$class]->merge($builder);
        } else {
            $this->builders[$class] = $builder;
        }
    }

    /**
     * Find the class name by the the class name or the Doctrine resolved target.
     *
     * @param string $class The class name
     */
    protected function findClassName(string $class): string
    {
        return $this->resolveTargets[$class] ?? $class;
    }
}
