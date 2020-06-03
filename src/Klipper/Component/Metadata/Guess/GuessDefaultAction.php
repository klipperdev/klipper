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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessDefaultAction implements
    GuessActionConfigInterface
{
    public function guessActionConfig(ActionMetadataBuilderInterface $builder): void
    {
        $defaultAction = $builder->getParent()->getDefaultAction();

        if (null === $defaultAction) {
            return;
        }

        if (empty($builder->getMethods()) && !empty($defaultAction->getMethods())) {
            $builder->setMethods($defaultAction->getMethods());
        }

        if (empty($builder->getSchemes()) && !empty($defaultAction->getSchemes())) {
            $builder->setSchemes($defaultAction->getSchemes());
        }

        if (null === $builder->getHost() && null !== $defaultAction->getHost()) {
            $builder->setHost($defaultAction->getHost());
        }

        if (null === $builder->getPath() && null !== $defaultAction->getPath()) {
            $builder->setPath($defaultAction->getPath());
        }

        if (null === $builder->getFragment() && null !== $defaultAction->getFragment()) {
            $builder->setFragment($defaultAction->getFragment());
        }

        $builder->setDefaults(array_merge($defaultAction->getDefaults(), $builder->getDefaults()));
        $builder->setRequirements(array_merge($defaultAction->getRequirements(), $builder->getRequirements()));
        $builder->setOptions(array_merge($defaultAction->getOptions(), $builder->getOptions()));

        if (null !== $defaultCondition = $defaultAction->getCondition()) {
            if (null === $condition = $builder->getCondition()) {
                $builder->setCondition($defaultCondition);
            } else {
                $builder->setCondition($defaultCondition.' && '.$condition);
            }
        }

        $builder->setConfigurations(array_merge($defaultAction->getConfigurations(), $builder->getConfigurations()));
    }
}
