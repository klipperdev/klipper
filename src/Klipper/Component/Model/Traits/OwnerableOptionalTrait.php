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
use Klipper\Component\Security\Model\Traits\OwnerableOptionalTrait as BaseOwnerableOptionalTrait;
use Klipper\Component\Security\Model\UserInterface;

/**
 * Trait of ownerable optional model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OwnerableOptionalTrait
{
    use BaseOwnerableOptionalTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Component\Security\Model\UserInterface", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     *
     * @Gedmo\Blameable(on="create")
     *
     * @Serializer\Expose
     */
    protected ?UserInterface $owner = null;
}
