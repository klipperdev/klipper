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
interface MetadataDynamicLoaderInterface extends MetadataLoaderInterface
{
    /**
     * Load the map of metadata short names and class names.
     */
    public function loadNames(): ObjectMetadataNameCollection;

    /**
     * Load the the object metadata builder.
     *
     * @param string $class The class name
     */
    public function loadBuilder(string $class): ?ObjectMetadataBuilderInterface;
}
