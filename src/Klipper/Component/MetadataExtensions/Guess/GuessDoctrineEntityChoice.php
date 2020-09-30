<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Guess;

use Klipper\Component\Metadata\AssociationMetadataBuilderInterface;
use Klipper\Component\Metadata\Guess\GuessAssociationConfigInterface;
use Klipper\Component\Metadata\Guess\GuessRegistryAwareInterface;
use Klipper\Component\Metadata\MetadataRegistryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessDoctrineEntityChoice extends AbstractGuessDoctrine implements
    GuessRegistryAwareInterface,
    GuessAssociationConfigInterface
{
    protected ?MetadataRegistryInterface $metadataRegistry = null;

    public function setRegistry(MetadataRegistryInterface $registry): void
    {
        $this->metadataRegistry = $registry;
    }

    /**
     * @throws
     */
    public function guessAssociationConfig(AssociationMetadataBuilderInterface $builder): void
    {
        $inputConfig = $builder->getInputConfig() ?? [];

        if (!isset($inputConfig['choices'])) {
            return;
        }

        if (isset($inputConfig['name_path']) && null !== $builder->getFormType()) {
            $builder->setFormOptions(array_merge($builder->getFormOptions() ?? [], [
                'choice_value' => $inputConfig['name_path'],
            ]));
        }

        if (isset($inputConfig['criteria']) && \is_array($inputConfig['criteria']) && null !== $builder->getFormType()) {
            $builder->setFormOptions(array_merge($builder->getFormOptions() ?? [], [
                'criteria' => $inputConfig['criteria'],
            ]));
        }
    }
}
