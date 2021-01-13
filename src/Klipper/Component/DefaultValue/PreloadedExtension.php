<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue;

use Klipper\Component\DefaultValue\Exception\InvalidArgumentException;

/**
 * A object default value extension with preloaded types and type exceptions.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PreloadedExtension implements ObjectExtensionInterface
{
    private array $types;

    private array $typeExtensions;

    /**
     * Creates a new preloaded extension.
     *
     * @param array $types          The types that the extension should support
     * @param array $typeExtensions The type extensions that the extension should support
     */
    public function __construct(array $types, array $typeExtensions)
    {
        $this->types = $types;
        $this->typeExtensions = $typeExtensions;
    }

    public function getType(string $name): ObjectTypeInterface
    {
        if (!isset($this->types[$name])) {
            throw new InvalidArgumentException(sprintf('The object default value type "%s" can not be loaded by this extension', $name));
        }

        return $this->types[$name];
    }

    public function hasType(string $name): bool
    {
        return isset($this->types[$name]);
    }

    public function getTypeExtensions(string $name): array
    {
        return $this->typeExtensions[$name]
            ?? [];
    }

    public function hasTypeExtensions(string $name): bool
    {
        return !empty($this->typeExtensions[$name]);
    }
}
