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
use Klipper\Component\Routing\Util\HostUtil;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class HostAutoConfigPassLoader implements PassLoaderInterface
{
    private array $patterns;

    private ?ParameterBagInterface $parameterBag;

    /**
     * @param array $patterns The map of patterns to inject route config
     */
    public function __construct(array $patterns = [], ?ParameterBagInterface $parameterBag = null)
    {
        $this->patterns = $patterns;
        $this->parameterBag = $parameterBag;
    }

    public function load(RouteCollection $collection): RouteCollection
    {
        if (empty($this->patterns)) {
            return $collection;
        }

        foreach ($collection->all() as $route) {
            $host = $route->getHost();
            $path = $route->getPath();

            if (null !== $this->parameterBag) {
                $host = $this->parameterBag->resolveValue($host);
                $path = $this->parameterBag->resolveValue($path);
            }

            foreach ($this->patterns as $routePattern => $config) {
                if (HostUtil::isRouteValid($routePattern, $host, $path)) {
                    $this->configureSchemes($route, $config);
                    $this->configureDefaults($route, $config);
                    $this->configureRequirements($route, $config);
                }
            }
        }

        return $collection;
    }

    /**
     * Configure the defaults of route.
     */
    private function configureDefaults(Route $route, array $config): void
    {
        if (isset($config['defaults'])) {
            foreach ($config['defaults'] as $default => $value) {
                if (!$route->hasDefault($default)) {
                    $route->setDefault($default, $value);
                }
            }
        }
    }

    /**
     * Configure the requirements of route.
     */
    private function configureRequirements(Route $route, array $config): void
    {
        if (isset($config['requirements']) && false !== strpos($path = $route->getPath(), '{')) {
            preg_match_all('/\{([a-zA-Z0-9\_]+)\}/', $path, $matches);

            foreach ($matches[1] as $parameter) {
                foreach ($config['requirements'] as $pattern => $replacement) {
                    if (fnmatch($pattern, $parameter) && !$route->hasRequirement($parameter)) {
                        $route->setRequirement($parameter, $replacement);
                    }
                }
            }
        }
    }

    /**
     * Configure the schemes of route.
     */
    private function configureSchemes(Route $route, array $config): void
    {
        if (isset($config['schemes'])) {
            $route->setSchemes(array_unique(array_merge($route->getSchemes(), (array) $config['schemes'])));
        }
    }
}
