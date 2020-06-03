<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtensionsExtra\Form\Extension;

use Klipper\Component\Form\Doctrine\Type\EntityType;

/**
 * Klipper Translatable Entity Form Extension.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperTranslatableEntityExtension extends AbstractTranslatableEntityExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [EntityType::class];
    }
}
