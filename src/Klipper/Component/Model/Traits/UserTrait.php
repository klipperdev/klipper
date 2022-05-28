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
use Klipper\Component\Security\Model\Traits\UserTrait as BaseUserTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of user model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait UserTrait
{
    use BaseUserTrait;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     *
     * @Assert\Length(max=50)
     * @Assert\Regex(pattern="/^[A-Za-z0-9\_\-.]+$/")
     *
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Public"})
     */
    protected ?string $username = null;

    /**
     * The hashed password.
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(groups={"edit"})
     */
    protected ?string $password = null;
}
