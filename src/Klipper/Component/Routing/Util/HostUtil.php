<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Routing\Util;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class HostUtil
{
    /**
     * Check if the host is valid for the auto configuration.
     *
     * @param string $pattern The pattern
     * @param string $host    The request host
     * @param string $path    The request path
     */
    public static function isRouteValid(string $pattern, string $host, string $path): bool
    {
        list($hostPattern, $pathPattern) = self::getHostPathPatterns($pattern);

        if ($hostPattern && 0 === strpos($hostPattern, '/')) {
            $hostValid = (bool) preg_match($hostPattern, $host);
        } else {
            $hostValid = fnmatch($hostPattern, $host);
        }

        if (!$hostValid) {
            return false;
        }

        return 0 === strpos($path, $pathPattern);
    }

    /**
     * Split the host and path patterns.
     *
     * @param string $pattern The pattern
     *
     * @return string[] The host and path patterns
     */
    public static function getHostPathPatterns(string $pattern): array
    {
        if ($pattern && 0 === strpos($pattern, '/')) {
            // regex
            if (false !== ($pos = strpos($pattern, '/|/'))) {
                $hostPattern = substr($pattern, 0, $pos + 1);
                $pathPattern = substr($pattern, $pos + 2);
            } else {
                $hostPattern = $pattern;
                $pathPattern = '/';
            }
        } elseif (false !== ($pos = strpos($pattern, '/'))) {
            // glob
            $hostPattern = substr($pattern, 0, $pos);
            $pathPattern = substr($pattern, $pos);
        } else {
            // root
            $hostPattern = $pattern;
            $pathPattern = '/';
        }

        return [$hostPattern, $pathPattern];
    }
}
