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
    /**
     * @var null|ObjectMetadataBuilderInterface
     */
    protected $parent;

    /**
     * @var null|bool
     */
    protected $readOnly;

    /**
     * @var null|bool
     */
    protected $required;

    /**
     * @var null|string
     */
    protected $input;

    /**
     * @var null|array
     */
    protected $inputConfig;

    /**
     * {@inheritdoc}
     */
    public function setParent(ObjectMetadataBuilderInterface $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ObjectMetadataBuilderInterface
    {
        if (null === $this->parent) {
            throw new BadMethodCallException('Metadata builder getParent method cannot be accessed if the child metadata is not added in the object metadata');
        }

        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setReadOnly(?bool $readOnly): self
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isReadOnly(): ?bool
    {
        return $this->readOnly;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequired(?bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isRequired(): ?bool
    {
        return $this->required;
    }

    /**
     * {@inheritdoc}
     */
    public function setInput(?string $input): self
    {
        $this->input = $input;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getInput(): ?string
    {
        return $this->input;
    }

    /**
     * {@inheritdoc}
     */
    public function setInputConfig(?array $inputConfig): self
    {
        $this->inputConfig = $inputConfig;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputConfig(): ?array
    {
        return $this->inputConfig;
    }
}
