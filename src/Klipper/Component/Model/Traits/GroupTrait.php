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
use Klipper\Component\Security\Model\Traits\GroupTrait as BaseGroupTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of group model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait GroupTrait
{
    use BaseGroupTrait;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Type(type="string")
     * @Assert\Length(min=3, max=255)
     * @Assert\Regex(pattern="/^[A-Za-z0-9\_]+$/")
     * @Assert\Regex(pattern="/__/", match=false, message="group.name.not_use_double_underscore")
     * @Assert\Regex(pattern="/_$/", match=false, message="group.name.not_use_special_character_at_end")
     * @Assert\NotBlank
     * @Serializer\Expose
     */
    protected ?string $name = null;

    /**
     * @var string[]
     *
     * @ORM\Column(type="json")
     * @Serializer\Expose
     */
    protected array $roles = [];
}
