<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue\Extension\DependencyInjection;

use Klipper\Component\DefaultValue\Exception\InvalidArgumentException;
use Klipper\Component\DefaultValue\ObjectExtensionInterface;
use Klipper\Component\DefaultValue\ObjectTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DependencyInjectionExtension implements ObjectExtensionInterface
{
    public ?ContainerInterface $container = null;

    protected array $typeServiceIds;

    protected array $typeExtensionServiceIds;

    public function __construct(array $typeServiceIds, array $typeExtensionServiceIds)
    {
        $this->typeServiceIds = $typeServiceIds;
        $this->typeExtensionServiceIds = $typeExtensionServiceIds;
    }

    public function getType(string $classname): ObjectTypeInterface
    {
        if (!isset($this->typeServiceIds[$classname])) {
            throw new InvalidArgumentException(sprintf('The object default value type "%s" is not registered with the service container.', $classname));
        }

        /** @var ObjectTypeInterface $type */
        $type = $this->container->get($this->typeServiceIds[$classname]);

        if ($type->getClass() !== $classname) {
            throw new InvalidArgumentException(
                sprintf('The object default value type class name specified for the service "%s" does not match the actual class name. Expected "%s", given "%s"', $this->typeServiceIds[$classname], $classname, $type->getClass())
            );
        }

        return $type;
    }

    public function hasType(string $classname): bool
    {
        return isset($this->typeServiceIds[$classname]);
    }

    public function getTypeExtensions(string $classname): array
    {
        $extensions = [];

        if (isset($this->typeExtensionServiceIds[$classname])) {
            foreach ($this->typeExtensionServiceIds[$classname] as $serviceId) {
                $extensions[] = $this->container->get($serviceId);
            }
        }

        return $extensions;
    }

    public function hasTypeExtensions(string $classname): bool
    {
        return isset($this->typeExtensionServiceIds[$classname]);
    }
}
