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
class AssociationMetadataNotFoundException extends MetadataNotFoundException
{
    /**
     * Constructor.
     *
     * @param string $association The association name
     */
    public function __construct(string $association)
    {
        $message = sprintf('The "%s" association metadata does not exist', $association);

        parent::__construct($message);
    }
}
