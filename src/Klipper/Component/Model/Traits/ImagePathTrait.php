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
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Serializer\Expose
     * @Serializer\ReadOnlyProperty
     */
    protected ?string $imagePath = null;

    public function hasImage(): bool
    {
        return null !== $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function getImageExtension(): ?string
    {
        return null !== $this->imagePath ? pathinfo($this->imagePath, PATHINFO_EXTENSION) : null;
    }

    public function getPreferredImageExtension(): ?string
    {
        $ext = $this->getImageExtension();

        return 'svg' === $ext ? 'svg' : 'jpg';
    }
}
