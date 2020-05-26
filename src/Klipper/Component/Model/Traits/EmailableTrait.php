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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of email model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait EmailableTrait
{
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\Email
     * @Assert\Type(type="string")
     * @Assert\Length(max=180)
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     */
    protected ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
