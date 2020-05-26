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
 * Trait of add image path.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait ImagePathTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     *
     * @Serializer\Expose
     * @Serializer\ReadOnly
     */
    protected ?string $imagePath = null;

    /**
     * {@inheritdoc}
     */
    public function hasImage(): bool
    {
        return null !== $this->imagePath;
    }

    /**
     * {@inheritdoc}
     */
    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }
}
