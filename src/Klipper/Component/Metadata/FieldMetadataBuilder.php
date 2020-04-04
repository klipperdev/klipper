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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class FieldMetadataBuilder extends BaseChildMetadataBuilder implements FieldMetadataBuilderInterface
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var null|bool
     */
    protected $sortable;

    /**
     * @var null|bool
     */
    protected $filterable;

    /**
     * @var null|bool
     */
    protected $searchable;

    /**
     * @var null|bool
     */
    protected $translatable;

    /**
     * Constructor.
     *
     * @param string      $field The field name
     * @param null|string $type  The type
     */
    public function __construct(string $field, ?string $type = null)
    {
        parent::__construct($type);

        $this->field = $field;
    }

    /**
     * {@inheritdoc}
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * {@inheritdoc}
     */
    public function setSortable(?bool $sortable): self
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isSortable(): ?bool
    {
        return $this->sortable;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilterable(?bool $filterable): self
    {
        $this->filterable = $filterable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isFilterable(): ?bool
    {
        return $this->filterable;
    }

    /**
     * {@inheritdoc}
     */
    public function setSearchable(?bool $searchable): self
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isSearchable(): ?bool
    {
        return $this->searchable;
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslatable(?bool $translatable): self
    {
        $this->translatable = $translatable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isTranslatable(): ?bool
    {
        return $this->translatable;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(FieldMetadataBuilderInterface $builder): self
    {
        BuilderUtil::mergeValues($this, $builder);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ObjectMetadataInterface $parent): FieldMetadataInterface
    {
        BuilderUtil::validate($this, ['name', 'type', 'label']);

        return new FieldMetadata(
            $parent,
            $this->getField(),
            (string) $this->getName(),
            (string) $this->getType(),
            (string) $this->getLabel(),
            $this->getDescription(),
            $this->getTranslationDomain(),
            $this->isPublic() ?? false,
            $this->isSortable() ?? false,
            $this->isFilterable() ?? false,
            $this->isSearchable() ?? false,
            $this->isTranslatable() ?? false,
            $this->isReadOnly() ?? false,
            $this->isRequired() ?? false,
            $this->getInput(),
            $this->getInputConfig(),
            $this->getFormType(),
            $this->getFormOptions(),
            $this->getGroups()
        );
    }
}
