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

/**
 * Organizational routing interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface OrganizationalRoutingInterface extends TranslatableRoutingInterface
{
    /**
     * Get the path for personal or organization.
     *
     * @param string $name       The route name for organization
     * @param array  $parameters The parameters of routes
     * @param bool   $relative   Check if path must be relative or not
     */
    public function getOrgPath(string $name, array $parameters = [], bool $relative = false): string;

    /**
     * Get the path for personal or organization with language query parameter.
     *
     * @param string $name       The route name for organization
     * @param array  $parameters The parameters of routes
     * @param bool   $relative   Check if path must be relative or not
     */
    public function getLangOrgPath(string $name, array $parameters = [], bool $relative = false): string;

    /**
     * Get the url for personal or organization.
     *
     * @param string $name           The route name for organization
     * @param array  $parameters     The parameters of routes
     * @param bool   $schemeRelative Check if the scheme must be relative or not
     */
    public function getOrgUrl(string $name, array $parameters = [], bool $schemeRelative = false): string;

    /**
     * Get the url for personal or organization with language query parameter.
     *
     * @param string $name           The route name for organization
     * @param array  $parameters     The parameters of routes
     * @param bool   $schemeRelative Check if the scheme must be relative or not
     */
    public function getLangOrgUrl(string $name, array $parameters = [], bool $schemeRelative = false): string;

    /**
     * Get the route parameters with the current organization name for all
     * organizational routes.
     *
     * @param string $name       The route name
     * @param array  $parameters The route parameters
     */
    public function getOrgParameters(string $name, array $parameters = []): array;
}
