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
class ChoiceNotFoundException extends MetadataNotFoundException
{
    /**
     * @param string $name The choice name
     */
    public function __construct(string $name)
    {
        $message = sprintf('The "%s" choice does not exist', $name);

        parent::__construct($message);
    }
}
