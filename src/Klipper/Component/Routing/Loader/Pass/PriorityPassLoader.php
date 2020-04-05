<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Routing\Loader\Pass;

use Klipper\Component\Routing\Loader\PassLoaderInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PriorityPassLoader implements PassLoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(RouteCollection $collection): RouteCollection
    {
        $routes = [];
        $systemRoutes = [];

        foreach ($collection->all() as $name => $route) {
            $priority = 0;

            if ($route->hasDefault('_priority')) {
                $priority = (int) $route->getDefault('_priority');
                $defaults = $route->getDefaults();
                unset($defaults['_priority']);
                $route->setDefaults($defaults);
            }

            if (0 === strpos($name, '_')) {
                $systemRoutes[] = [$name, $route];
            } else {
                $routes[$priority][] = [$name, $route];
            }

            $collection->remove($name);
        }

        foreach ($systemRoutes as $systemRoute) {
            $collection->add($systemRoute[0], $systemRoute[1]);
        }

        if ($routes) {
            krsort($routes);
            $routes = array_merge(...$routes);

            foreach ($routes as $route) {
                $collection->add($route[0], $route[1]);
            }
        }

        return $collection;
    }
}