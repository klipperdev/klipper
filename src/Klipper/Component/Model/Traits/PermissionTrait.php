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
use Klipper\Component\Security\Model\Traits\PermissionTrait as BasePermissionTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of permission model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait PermissionTrait
{
    use BasePermissionTrait;

    /**
     * @var string[]
     *
     * @ORM\Column(type="json", nullable=true)
     * @Assert\Choice(multiple=true, callback={"Klipper\Component\SecurityExtra\Choice\PermissionContext", "getValues"})
     */
    protected array $contexts = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected ?string $class = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected ?string $field = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\NotBlank
     */
    protected ?string $operation = null;
}
