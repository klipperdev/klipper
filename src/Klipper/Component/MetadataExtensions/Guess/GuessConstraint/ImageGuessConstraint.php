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
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Image;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ImageGuessConstraint extends AbstractGuessConstraint
{
    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool
    {
        return $constraint instanceof Image;
    }

    /**
     * @param Constraint|Image $constraint
     */
    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void
    {
        $this->addType($builder, '?blob');
        $this->addInput($builder, 'image', [
            'allow_landscape' => $constraint->allowLandscape,
            'allow_portrait' => $constraint->allowPortrait,
            'allow_square' => $constraint->allowSquare,
            'max_size' => $constraint->maxSize,
            'mime_types' => $constraint->mimeTypes,
            'min_pixels' => $constraint->minPixels,
            'max_pixels' => $constraint->maxPixels,
            'min_ratio' => $constraint->minRatio,
            'max_ratio' => $constraint->maxRatio,
            'min_width' => $constraint->minWidth,
            'max_width' => $constraint->maxWidth,
            'min_height' => $constraint->minHeight,
            'max_height' => $constraint->maxHeight,
        ]);
    }
}
