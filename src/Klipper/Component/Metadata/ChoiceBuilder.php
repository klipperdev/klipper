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

use Klipper\Component\Metadata\Util\BuilderUtil;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ChoiceBuilder implements ChoiceBuilderInterface
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
     * @var null|array
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
     * @param string      $name              The unique name
     * @param null|string $translationDomain The translation domain
     * @param array       $identifiers       The list identifiers
     * @param array       $values            The values
     * @param null|string $placeholder       The placeholder
     */
    public function __construct(
        string $name,
        ?string $translationDomain,
        array $identifiers,
        ?array $values = null,
        ?string $placeholder = null
    ) {
        $this->name = $name;
        $this->listIdentifiers = $identifiers;
        $this->values = $values;
        $this->translationDomain = $translationDomain;
        $this->placeholder = $placeholder;
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
    public function getListIdentifiers(): ?array
    {
        return $this->listIdentifiers;
    }

    /**
     * {@inheritdoc}
     */
    public function setListIdentifiers(array $identifiers)
    {
        $this->listIdentifiers = $identifiers;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): ?array
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function setValues(?array $values): self
    {
        $this->values = $values;

        return $this;
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
    public function setTranslationDomain(?string $translationDomain): self
    {
        $this->translationDomain = $translationDomain;

        return $this;
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
    public function setPlaceholder(?string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResources(): array
    {
        return array_values($this->resources);
    }

    /**
     * {@inheritdoc}
     */
    public function addResource(ResourceInterface $resource): void
    {
        $key = (string) $resource;

        if (!isset($this->resources[$key])) {
            $this->resources[$key] = $resource;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function merge(ChoiceBuilderInterface $builder): self
    {
        BuilderUtil::mergeValues($this, $builder);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build(): ChoiceInterface
    {
        return new Choice(
            $this->getName(),
            $this->getListIdentifiers() ?? [],
            $this->getValues() ?? self::getFlatValues($this->listIdentifiers),
            $this->getTranslationDomain(),
            $this->getPlaceholder(),
            $this->getResources()
        );
    }

    /**
     * Get all values of the grouped choice identifiers keys.
     *
     * @param array $choiceIdentifiers The choice identifiers
     *
     * @return string[]
     */
    private static function getFlatValues(array $choiceIdentifiers): array
    {
        $choices = [];

        foreach ($choiceIdentifiers as $key => $value) {
            if (\is_array($value)) {
                $choices = array_merge($choices, self::getFlatValues($value));
            } else {
                $choices[] = $key;
            }
        }

        return $choices;
    }
}
