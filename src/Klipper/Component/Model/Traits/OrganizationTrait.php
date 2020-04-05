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
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\Security\Model\OrganizationUserInterface;
use Klipper\Component\Security\Model\Traits\OrganizationTrait as BaseOrganizationTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of organization model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OrganizationTrait
{
    use BaseOrganizationTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Regex(pattern="/^[A-Za-z0-9\_\-.]+$/")
     * @Assert\Length(max=255)
     *
     * @Serializer\Expose
     */
    protected $name;

    /**
     * @var null|Collection|OrganizationUserInterface[]
     *
     * @ORM\OneToMany(
     *     targetEntity="Klipper\Component\Security\Model\OrganizationUserInterface",
     *     mappedBy="organization",
     *     fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"}
     * )
     *
     * @Assert\Valid(traverse=false)
     */
    protected $organizationUsers;
}
