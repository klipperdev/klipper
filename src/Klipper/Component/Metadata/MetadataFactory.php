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

use Klipper\Component\Metadata\Exception\ObjectMetadataNotFoundException;
use Klipper\Component\Metadata\Loader\ChoiceNameCollection;
use Klipper\Component\Metadata\Loader\ObjectMetadataNameCollection;
use Klipper\Component\Resource\Domain\DomainInterface;
use Klipper\Component\Resource\Domain\DomainManagerInterface;
use Klipper\Component\Resource\Exception\InvalidArgumentException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MetadataFactory implements MetadataFactoryInterface
{
    protected MetadataRegistryInterface $register;

    protected DomainManagerInterface $domainManager;

    protected ?ChoiceNameCollection $cacheChoiceNames = null;

    /**
     * @param MetadataRegistryInterface $register      The metadata register
     * @param DomainManagerInterface    $domainManager The resource domain manager
     */
    public function __construct(
        MetadataRegistryInterface $register,
        DomainManagerInterface $domainManager
    ) {
        $this->register = $register;
        $this->domainManager = $domainManager;
    }

    public function getManagedClasses(): ObjectMetadataNameCollection
    {
        return $this->register->getNames();
    }

    public function isManagedClass(string $class): bool
    {
        return $this->domainManager->has($class);
    }

    public function isManagedByName(string $name): bool
    {
        $names = $this->getManagedClasses()->all();

        return isset($names[$name]);
    }

    public function getManagedClass(string $class): string
    {
        return $this->getManagedDomain($class)->getClass();
    }

    public function create(string $class): ObjectMetadataInterface
    {
        $domain = $this->getManagedDomain($class);

        return $this->getBuilder($domain->getClass())->build();
    }

    public function createChoice(string $name): ChoiceInterface
    {
        $builder = $this->register->getChoice($name);

        // force to init all choices
        if (null === $builder && null === $this->cacheChoiceNames) {
            $this->getChoiceNames();
            $builder = $this->register->getChoice($name);
        }

        return ($builder ?? new ChoiceBuilder($name, null, []))->build();
    }

    public function getChoiceNames(): ChoiceNameCollection
    {
        if (null === $this->cacheChoiceNames) {
            foreach ($this->getManagedClasses()->all() as $class) {
                $this->getBuilder($class);
            }

            $this->cacheChoiceNames = $this->register->getChoiceNames();
        }

        return $this->cacheChoiceNames;
    }

    public function isChoiceManaged(string $name): bool
    {
        $names = $this->getChoiceNames()->all();

        return \in_array($name, $names, true);
    }

    /**
     * Get the object metadata builder.
     *
     * @param string $class The class name
     */
    protected function getBuilder(string $class): ObjectMetadataBuilderInterface
    {
        $builder = $this->register->getBuilder($class);

        return $builder ?? $this->register->guessConfig(new ObjectMetadataBuilder($class));
    }

    /**
     * Get the managed resource domain.
     *
     * @param string $class The class name
     *
     * @throws ObjectMetadataNotFoundException When the class is not registered in doctrine
     */
    private function getManagedDomain(string $class): DomainInterface
    {
        try {
            $names = $this->getManagedClasses()->all();
            $class = $names[$class] ?? $class;
            $domain = $this->domainManager->get($class);
        } catch (InvalidArgumentException $e) {
            throw new ObjectMetadataNotFoundException($class);
        } catch (\ReflectionException $e) {
            throw new ObjectMetadataNotFoundException($class);
        }

        return $domain;
    }
}
