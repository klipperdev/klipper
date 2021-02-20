<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Routing;

use Klipper\Component\Routing\TranslatableRoutingInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PortalRoutingInterface extends TranslatableRoutingInterface
{
    /**
     * Get the path for portal.
     *
     * @param string $name       The route name for portal
     * @param array  $parameters The parameters of routes
     * @param bool   $relative   Check if path must be relative or not
     */
    public function getPortalPath(string $name, array $parameters = [], bool $relative = false): string;

    /**
     * Get the path for portal with language query parameter.
     *
     * @param string $name       The route name for portal
     * @param array  $parameters The parameters of routes
     * @param bool   $relative   Check if path must be relative or not
     */
    public function getLangPortalPath(string $name, array $parameters = [], bool $relative = false): string;

    /**
     * Get the url for portal.
     *
     * @param string $name           The route name for portal
     * @param array  $parameters     The parameters of routes
     * @param bool   $schemeRelative Check if the scheme must be relative or not
     */
    public function getPortalUrl(string $name, array $parameters = [], bool $schemeRelative = false): string;

    /**
     * Get the url for portal with language query parameter.
     *
     * @param string $name           The route name for portal
     * @param array  $parameters     The parameters of routes
     * @param bool   $schemeRelative Check if the scheme must be relative or not
     */
    public function getLangPortalUrl(string $name, array $parameters = [], bool $schemeRelative = false): string;

    /**
     * Get the route parameters with the current portal name for all
     * portal routes.
     *
     * @param string $name       The route name
     * @param array  $parameters The route parameters
     */
    public function getPortalParameters(string $name, array $parameters = []): array;
}
