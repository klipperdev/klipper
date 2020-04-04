<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Routing\Loader;

use Symfony\Component\Routing\RouteCollection;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PassLoaderInterface
{
    /**
     * Load the routes in existing route collection.
     *
     * @param RouteCollection $collection The route collection
     */
    public function load(RouteCollection $collection): RouteCollection;
}
