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
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $listIdentifiers;

    /**
     * @var string[]
     */
    protected $values;

    /**
     * @var null|string
     */
    protected $translationDomain;

    /**
     * @var null|string
     */
    protected $placeholder;

    /**
     * @var ResourceInterface[]
     */
    protected $resources = [];

    /**
     * Constructor.
     *
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

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getListIdentifiers(): array
    {
        return $this->listIdentifiers;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    /**
     * {@inheritdoc}
     */
    public function getResources(): array
    {
        return $this->resources;
    }
}
