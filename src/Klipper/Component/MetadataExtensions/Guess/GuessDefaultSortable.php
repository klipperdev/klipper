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

use Klipper\Component\Metadata\Guess\GuessObjectConfigInterface;
use Klipper\Component\Metadata\ObjectMetadataBuilderInterface;
use Klipper\Contracts\Model\SortableInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessDefaultSortable implements GuessObjectConfigInterface
{
    public function guessObjectConfig(ObjectMetadataBuilderInterface $builder): void
    {
        if (is_a($builder->getClass(), SortableInterface::class, true)
                && empty($builder->getDefaultSortable())) {
            $builder->setDefaultSortable(['position' => 'asc']);
        }
    }
}
