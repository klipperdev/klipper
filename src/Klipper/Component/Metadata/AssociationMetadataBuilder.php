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
class AssociationMetadataBuilder extends BaseChildMetadataBuilder implements AssociationMetadataBuilderInterface
{
    /**
     * @var string
     */
    protected $association;

    /**
     * @var null|string
     */
    protected $target;

    /**
     * Constructor.
     *
     * @param string      $association The association name
     * @param null|string $type        The type
     * @param null|string $target      The target
     */
    public function __construct(
        string $association,
        ?string $type = null,
        ?string $target = null
    ) {
        parent::__construct($type);

        $this->association = $association;
        $this->target = $target;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociation(): string
    {
        return $this->association;
    }

    /**
     * {@inheritdoc}
     */
    public function setTarget(?string $target): self
    {
        $this->target = $target;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget(): ?string
    {
        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(AssociationMetadataBuilderInterface $builder): self
    {
        BuilderUtil::mergeValues($this, $builder);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ObjectMetadataInterface $parent): AssociationMetadataInterface
    {
        BuilderUtil::validate($this, ['name', 'type', 'target', 'label']);

        return new AssociationMetadata(
            $parent,
            $this->getAssociation(),
            (string) $this->getName(),
            (string) $this->getType(),
            (string) $this->getTarget(),
            (string) $this->getLabel(),
            $this->getDescription(),
            $this->getTranslationDomain(),
            $this->isPublic() ?? false,
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
