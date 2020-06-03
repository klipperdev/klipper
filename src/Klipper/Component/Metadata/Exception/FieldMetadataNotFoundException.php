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
class FieldMetadataNotFoundException extends MetadataNotFoundException
{
    /**
     * @param string $field The field name
     */
    public function __construct(string $field)
    {
        $message = sprintf('The "%s" field metadata does not exist', $field);

        parent::__construct($message);
    }
}
