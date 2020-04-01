<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Security\Tests\Fixtures\Model;

use Klipper\Component\Security\Model\Traits\EditGroupableInterface;
use Klipper\Component\Security\Model\Traits\EditGroupableTrait;
use Klipper\Component\Security\Model\Traits\RoleableInterface;
use Klipper\Component\Security\Model\Traits\RoleableTrait;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MockOrganizationUserRoleableGroupable extends MockOrganizationUser implements RoleableInterface, EditGroupableInterface
{
    use RoleableTrait;
    use EditGroupableTrait;
}
