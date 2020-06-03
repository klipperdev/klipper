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
    protected ObjectMetadataInterface $parent;

    protected bool $readOnly = false;

    protected bool $required = false;

    protected ?string $input = null;

    protected ?array $inputConfig = null;

    public function getParent(): ObjectMetadataInterface
    {
        return $this->parent;
    }

    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function getInputConfig(): ?array
    {
        return $this->inputConfig;
    }
}
