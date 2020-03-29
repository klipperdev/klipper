<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtra\Exception;

/**
 * Base ObjectManagerNotFoundException for the doctrine extra.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ObjectManagerNotFoundException extends InvalidArgumentException
{
    /**
     * Create the exception.
     *
     * @param string $class The object class managed by doctrine
     */
    public static function create(string $class): ObjectManagerNotFoundException
    {
        $msg = sprintf('The doctrine manager for the "%s" class is not found', $class);

        return new self($msg);
    }
}
