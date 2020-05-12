<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Routing;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Routing interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface RoutingInterface
{
    /**
     * Get the URL generator.
     */
    public function getUrlGenerator(): UrlGeneratorInterface;

    /**
     * Get the path.
     *
     * @param string $name       The route name
     * @param array  $parameters The parameters of routes
     * @param bool   $relative   Check if path must be relative or not
     */
    public function getPath(string $name, array $parameters = [], bool $relative = false): string;

    /**
     * Get the url.
     *
     * @param string $name           The route name for organization
     * @param array  $parameters     The parameters of routes
     * @param bool   $schemeRelative Check if the scheme must be relative or not
     */
    public function getUrl(string $name, array $parameters = [], bool $schemeRelative = false): string;
}
