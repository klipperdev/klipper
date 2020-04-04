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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class BaseChildMetadata extends BaseMetadata implements ChildMetadataInterface
{
    /**
     * @var ObjectMetadataInterface
     */
    protected $parent;

    /**
     * @var bool
     */
    protected $readOnly;

    /**
     * @var bool
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
    public function getParent(): ObjectMetadataInterface
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * {@inheritdoc}
     */
    public function isRequired(): bool
    {
        return $this->required;
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
    public function getInputConfig(): ?array
    {
        return $this->inputConfig;
    }
}
