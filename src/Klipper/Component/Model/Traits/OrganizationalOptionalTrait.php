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
use Klipper\Component\Security\Model\OrganizationInterface;
use Klipper\Component\Security\Model\Traits\OrganizationalOptionalTrait as BaseOrganizationalOptionalTrait;

/**
 * Trait of organizational optional model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OrganizationalOptionalTrait
{
    use BaseOrganizationalOptionalTrait;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Component\Security\Model\OrganizationInterface",
     *     fetch="EXTRA_LAZY"
     * )
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Serializer\Type("AssociationId")
     * @Serializer\Expose
     * @Serializer\ReadOnlyProperty
     */
    protected ?OrganizationInterface $organization = null;
}
