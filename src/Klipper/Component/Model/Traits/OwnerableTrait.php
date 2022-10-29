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
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\Security\Model\Traits\OwnerableTrait as BaseOwnerableTrait;
use Klipper\Component\Security\Model\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of ownerable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OwnerableTrait
{
    use BaseOwnerableTrait;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Component\Security\Model\UserInterface"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     * @Gedmo\Blameable(on="create")
     * @Assert\NotNull
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    protected ?UserInterface $owner = null;
}
