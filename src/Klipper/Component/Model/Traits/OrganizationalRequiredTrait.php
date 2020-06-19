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
use Klipper\Component\Security\Model\Traits\OrganizationalRequiredTrait as BaseOrganizationalRequiredTrait;

/**
 * Trait of organizational required model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OrganizationalRequiredTrait
{
    use BaseOrganizationalRequiredTrait;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Component\Security\Model\OrganizationInterface",
     *     fetch="EXTRA_LAZY"
     * )
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Serializer\Type("Relation")
     * @Serializer\Expose
     * @Serializer\ReadOnly
     */
    protected ?OrganizationInterface $organization = null;
}
