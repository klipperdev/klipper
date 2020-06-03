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
class ActionMetadataNotFoundException extends MetadataNotFoundException
{
    /**
     * @param string $action The action name
     */
    public function __construct(string $action)
    {
        $message = sprintf('The "%s" action metadata does not exist', $action);

        parent::__construct($message);
    }
}
