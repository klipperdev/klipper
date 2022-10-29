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
 * Trait of add file path.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait FilePathTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Serializer\Expose
     * @Serializer\ReadOnlyProperty
     */
    protected ?string $filePath = null;

    public function hasFile(): bool
    {
        return null !== $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function getFileExtension(): ?string
    {
        return null !== $this->filePath ? pathinfo($this->filePath, PATHINFO_EXTENSION) : null;
    }
}
