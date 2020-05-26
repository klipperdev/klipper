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

use Doctrine\ORM\Mapping as ORM;
use Klipper\Component\Security\Model\Traits\OwnerableTrait as BaseOwnerableTrait;
use Klipper\Component\Security\Model\UserInterface;

/**
 * Trait of ownerable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OwnerableTrait
{
    use BaseOwnerableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Component\Security\Model\UserInterface")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    protected ?UserInterface $owner = null;
}
