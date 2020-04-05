<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Model\Traits;

/**
 * Interface of organizational optional model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface OrganizationalOptionalInterface extends
    \Klipper\Component\Security\Model\Traits\OrganizationalOptionalInterface,
    OrganizationalInterface
{
}
