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

use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Choice implements ChoiceInterface
{
    protected string $name;

    protected array $listIdentifiers;

    /**
     * @var string[]
     */
    protected array $values;

    protected ?string $translationDomain;

    protected ?string $placeholder;

    /**
     * @var ResourceInterface[]
     */
    protected array $resources = [];

    /**
     * @param string[]            $values
     * @param ResourceInterface[] $resources
     */
    public function __construct(
        string $name,
        array $listIdentifiers,
        array $values,
        ?string $translationDomain,
        ?string $placeholder,
        array $resources
    ) {
        $this->name = $name;
        $this->listIdentifiers = $listIdentifiers;
        $this->values = $values;
        $this->translationDomain = $translationDomain;
        $this->placeholder = $placeholder;
        $this->resources = $resources;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getListIdentifiers(): array
    {
        return $this->listIdentifiers;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function getResources(): array
    {
        return $this->resources;
    }
}
