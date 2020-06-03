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

use Klipper\Component\Metadata\Exception\BadMethodCallException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class BaseChildMetadataBuilder extends BaseMetadataBuilder implements ChildMetadataBuilderInterface
{
    protected ?ObjectMetadataBuilderInterface $parent = null;

    protected ?bool $readOnly = null;

    protected ?bool $required = null;

    protected ?string $input = null;

    protected ?array $inputConfig = null;

    public function setParent(ObjectMetadataBuilderInterface $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ObjectMetadataBuilderInterface
    {
        if (null === $this->parent) {
            throw new BadMethodCallException('Metadata builder getParent method cannot be accessed if the child metadata is not added in the object metadata');
        }

        return $this->parent;
    }

    public function setReadOnly(?bool $readOnly): self
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    public function isReadOnly(): ?bool
    {
        return $this->readOnly;
    }

    public function setRequired(?bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    public function isRequired(): ?bool
    {
        return $this->required;
    }

    public function setInput(?string $input): self
    {
        $this->input = $input;

        return $this;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function setInputConfig(?array $inputConfig): self
    {
        $this->inputConfig = $inputConfig;

        return $this;
    }

    public function getInputConfig(): ?array
    {
        return $this->inputConfig;
    }
}
