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
class ControllerHostAutoConfigPassLoader implements PassLoaderInterface
{
    private array $patterns;

    /**
     * @param array $patterns The map of patterns to inject route config
     */
    public function __construct(array $patterns = [])
    {
        $this->patterns = $patterns;
    }

    public function load(RouteCollection $collection): RouteCollection
    {
        if (empty($this->patterns)) {
            return $collection;
        }

        foreach ($collection->all() as $name => $route) {
            if (!$route->getHost() && $route->hasDefault('_controller')) {
                foreach ($this->patterns as $pattern => $configHost) {
                    if ($this->isValid($pattern, $name)
                            || $this->isValid($pattern, $route->getDefault('_controller'))) {
                        $route->setHost($configHost);

                        break;
                    }
                }
            }
        }

        return $collection;
    }

    /**
     * Check if the controller is valid for the auto configuration.
     *
     * @param string $pattern The pattern
     * @param string $string  The route controller or name
     */
    public function isValid(string $pattern, string $string): bool
    {
        return 0 === strpos($pattern, '/')
            ? (bool) preg_match($pattern, $string)
            : fnmatch($pattern, $string, FNM_NOESCAPE);
    }
}
