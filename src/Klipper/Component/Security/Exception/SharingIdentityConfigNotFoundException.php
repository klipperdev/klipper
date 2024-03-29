<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Security\Exception;

/**
 * SharingIdentityConfigNotFoundException for the Security component.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SharingIdentityConfigNotFoundException extends InvalidArgumentException
{
    /**
     * @param string $class The class name
     */
    public function __construct(string $class)
    {
        parent::__construct(sprintf('The sharing identity configuration for the class "%s" is not found', $class));
    }
}
