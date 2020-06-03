<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Exception;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ObjectMetadataNotFoundException extends MetadataNotFoundException
{
    /**
     * @param string $class The class name
     */
    public function __construct(string $class)
    {
        $message = sprintf('The "%s" object metadata does not exist', $class);

        parent::__construct($message);
    }
}
