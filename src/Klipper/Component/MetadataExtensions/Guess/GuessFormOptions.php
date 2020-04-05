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

use Klipper\Component\Metadata\ChildMetadataBuilderInterface;
use Klipper\Component\Metadata\MetadataRegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessFormOptions
{
    public static function choice(array $inputConfig, ChildMetadataBuilderInterface $builder, MetadataRegistryInterface $registry): array
    {
        $options = !is_a($builder->getFormType(), ChoiceType::class, true) ? [] : [
            'multiple' => isset($inputConfig['multiple']),
        ];

        if (isset($inputConfig['choices']) && \is_string($inputConfig['choices']) && 0 === strpos($inputConfig['choices'], '#/choices/')) {
            $choiceName = substr($inputConfig['choices'], 10);

            if (null !== $choice = $registry->getChoice($choiceName)) {
                $options = array_merge($options, [
                    'choices' => self::toFormChoices($choice->getListIdentifiers()),
                    'choice_translation_domain' => $choice->getTranslationDomain(),
                    'placeholder' => $choice->getPlaceholder(),
                ]);
            }
        }

        return $options;
    }

    /**
     * Convert the identifiers into form choices.
     *
     * @param array $identifiers The choice identifiers
     */
    private static function toFormChoices(array $identifiers): array
    {
        $choices = [];

        foreach ($identifiers as $key => $value) {
            if (\is_array($value)) {
                $choices[$key] = self::toFormChoices($value);
            } else {
                $choices[$value] = $key;
            }
        }

        return $choices;
    }
}
