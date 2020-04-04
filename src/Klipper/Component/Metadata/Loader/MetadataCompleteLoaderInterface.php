<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Loader;

use Klipper\Component\Metadata\ObjectMetadataBuilderInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface MetadataCompleteLoaderInterface extends MetadataLoaderInterface
{
    /**
     * Load the the object metadata builders.
     *
     * @return ObjectMetadataBuilderInterface[]
     */
    public function load(): array;
}
