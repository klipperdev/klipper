<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Guess\GuessConstraint;

use Klipper\Component\Metadata\ChildMetadataBuilderInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractGuessConstraint implements GuessConstraintInterface
{
    protected function addType(ChildMetadataBuilderInterface $builder, string $type): void
    {
        if (false === strpos($type, '?') || null === $builder->getType()) {
            $builder->setType(str_replace('?', '', $type));
        }
    }

    protected function addInput(ChildMetadataBuilderInterface $builder, string $input, array $config = []): void
    {
        $config = array_filter($config, static function ($value) {
            return null !== $value && '' !== $value;
        });
        $config = array_merge($builder->getInputConfig() ?? [], $config);

        if (false === strpos($input, '?') || null === $builder->getInput()) {
            $builder->setInput(str_replace('?', '', $input));
        }

        if (!empty($config)) {
            $builder->setInputConfig($config);
        }
    }
}
