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

/**
 * Trait of expirable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait ExpirableTrait
{
    /**
     * @ORM\Column(type="datetime")
     *
     * @\Symfony\Component\Validator\Constraints\Type(type="datetime")
     * @\Symfony\Component\Validator\Constraints\NotBlank
     *
     * @Serializer\Expose
     */
    protected ?\DateTimeInterface $expiresAt = null;

    /**
     * @see ExpirableInterface::setExpiresAt()
     */
    public function setExpiresAt(?\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * @see ExpirableInterface::getExpiresAt()
     */
    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }
}
