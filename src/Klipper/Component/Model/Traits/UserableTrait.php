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
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\Security\Model\UserInterface;
use Klipper\Component\SecurityExtra\Model\Traits\UserableTrait as BaseUserableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of userable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait UserableTrait
{
    use BaseUserableTrait;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Klipper\Component\Security\Model\UserInterface",
     *     cascade={"persist", "remove"}
     * )
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @Assert\NotNull
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    protected ?UserInterface $user = null;
}
