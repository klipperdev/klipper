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

use Klipper\Component\Security\Model\Traits\RoleHierarchicalTrait as BaseRoleHierarchicalTrait;

/**
 * Trait of roleable hierarchical model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait RoleHierarchicalTrait
{
    use BaseRoleHierarchicalTrait;
}
