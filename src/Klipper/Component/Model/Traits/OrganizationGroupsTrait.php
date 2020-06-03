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

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Klipper\Component\Security\Model\GroupInterface;
use Klipper\Component\Security\Model\Traits\OrganizationGroupsTrait as BaseOrganizationGroupsTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of organization groups model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OrganizationGroupsTrait
{
    use BaseOrganizationGroupsTrait;

    /**
     * @var null|Collection|GroupInterface[]
     *
     * @ORM\OneToMany(
     *     targetEntity="Klipper\Component\Security\Model\GroupInterface",
     *     mappedBy="organization",
     *     fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"}
     * )
     *
     * @Assert\Valid
     */
    protected ?Collection $organizationGroups = null;
}
