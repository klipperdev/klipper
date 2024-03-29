<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityExtra\Model\Traits;

use Klipper\Component\Security\Model\UserInterface;

/**
 * Trait of add dependency entity with an user.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait UserableEditableTrait
{
    use UserableTrait;

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }
}
