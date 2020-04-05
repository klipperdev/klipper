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
 * Interface of edit groupable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface EditGroupableInterface extends
    \Klipper\Component\Security\Model\Traits\EditGroupableInterface,
    GroupableInterface
{
}
