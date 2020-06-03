<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DependencyInjection;

use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ResolveEnvVarProcessor implements EnvVarProcessorInterface
{
    public function getEnv(string $prefix, string $name, \Closure $getEnv)
    {
        return preg_replace_callback('/%env\(([^%\s]+)\)%/', static function ($match) use ($getEnv) {
            return $getEnv($match[1]);
        }, $getEnv($name));
    }

    public static function getProvidedTypes(): array
    {
        return [
            'resolve_env' => 'string',
        ];
    }
}
