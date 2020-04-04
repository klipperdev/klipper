<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Guess;

use Klipper\Component\Metadata\ActionMetadataBuilderInterface;
use Klipper\Component\Metadata\AssociationMetadataBuilderInterface;
use Klipper\Component\Metadata\FieldMetadataBuilderInterface;
use Klipper\Component\Metadata\MetadataBuilderInterface;
use Klipper\Component\Metadata\ObjectMetadataBuilderInterface;
use Klipper\Component\Metadata\Util\MetadataUtil;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessDefault implements
    GuessObjectConfigInterface,
    GuessActionConfigInterface,
    GuessFieldConfigInterface,
    GuessAssociationConfigInterface
{
    /**
     * @var PropertyAccessorInterface
     */
    protected $accessor;

    /**
     * @var array
     */
    protected $defaults;

    /**
     * Constructor.
     *
     * @param array                          $defaults The default values
     * @param null|PropertyAccessorInterface $accessor The property accessor
     */
    public function __construct(
        array $defaults = [],
        PropertyAccessorInterface $accessor = null
    ) {
        $this->accessor = $accessor ?: PropertyAccess::createPropertyAccessor();
        $this->defaults = array_merge([
            'global' => [],
            'actions' => [],
            'fields' => [],
            'associations' => [],
        ], $defaults);
    }

    /**
     * {@inheritdoc}
     */
    public function guessObjectConfig(ObjectMetadataBuilderInterface $builder): void
    {
        $this->mergeDefaultValues($this->defaults['global'], $builder);
    }

    /**
     * {@inheritdoc}
     */
    public function guessActionConfig(ActionMetadataBuilderInterface $builder): void
    {
        $action = $builder->getName();

        if (isset($this->defaults['actions'][$action])) {
            foreach ($this->defaults['actions'][$action] as $property => $value) {
                if (MetadataUtil::canBeOverridden($this->accessor, $builder, $property)) {
                    $this->accessor->setValue($builder, $property, $value);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function guessFieldConfig(FieldMetadataBuilderInterface $builder): void
    {
        $field = $builder->getField();

        if (isset($this->defaults['fields'][$field])) {
            $this->mergeDefaultValues($this->defaults['fields'][$field], $builder);
        }

        $this->mergeDefaultValues($this->defaults['global'], $builder);
    }

    /**
     * {@inheritdoc}
     */
    public function guessAssociationConfig(AssociationMetadataBuilderInterface $builder): void
    {
        $association = $builder->getAssociation();

        if (isset($this->defaults['associations'][$association])) {
            $this->mergeDefaultValues($this->defaults['associations'][$association], $builder);
        }

        $this->mergeDefaultValues($this->defaults['global'], $builder);
    }

    /**
     * Merge the available default values for the builder.
     *
     * @param array                    $defaults The defaults values
     * @param MetadataBuilderInterface $builder  The metadata builder
     */
    protected function mergeDefaultValues(array $defaults, MetadataBuilderInterface $builder): void
    {
        foreach ($defaults as $property => $value) {
            if (MetadataUtil::canBeOverridden($this->accessor, $builder, $property)) {
                $this->accessor->setValue($builder, $property, $value);
            }
        }
    }
}
