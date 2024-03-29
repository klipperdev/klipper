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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PriorityPassLoader implements PassLoaderInterface
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function load(RouteCollection $collection): RouteCollection
    {
        $routes = [];
        $systemRoutes = [];

        foreach ($collection->all() as $name => $route) {
            $host = $route->getHost();
            $hostPriority = 0;
            $priority = 0;

            if ($route->hasDefault('_host_priority')) {
                $hostPriority = (int) $route->getDefault('_host_priority');
                $defaults = $route->getDefaults();
                unset($defaults['_host_priority']);
                $route->setDefaults($defaults);
            }

            if ($route->hasDefault('_priority')) {
                $priority = (int) $route->getDefault('_priority');
                $defaults = $route->getDefaults();
                unset($defaults['_priority']);
                $route->setDefaults($defaults);
            }

            if (0 === strpos($name, '_')) {
                $systemRoutes[] = [$name, $route];
            } else {
                $routes[$hostPriority][$this->parameterBag->resolveValue($host)][$priority][] = [$name, $route];
            }

            $collection->remove($name);
        }

        foreach ($systemRoutes as $systemRoute) {
            $collection->add($systemRoute[0], $systemRoute[1]);
        }

        if ($routes) {
            $priorityRoutes = array_keys($routes);
            krsort($priorityRoutes);

            foreach ($priorityRoutes as $hostPriority) {
                foreach ($routes[$hostPriority] as $hostRoutes) {
                    krsort($hostRoutes);
                    $hostRoutes = array_merge(...$hostRoutes);

                    foreach ($hostRoutes as $route) {
                        $collection->add($route[0], $route[1]);
                    }
                }
            }
        }

        return $collection;
    }
}
