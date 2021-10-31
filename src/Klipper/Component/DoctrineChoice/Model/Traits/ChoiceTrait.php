<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\Model\Traits\LabelableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of choice model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait ChoiceTrait
{
    use LabelableTrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\NotBlank
     *
     * @Gedmo\SortableGroup
     *
     * @Serializer\Expose
     */
    protected ?string $type = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     */
    protected ?string $value = null;

    /**
     * @ORM\Column(type="string", length=9, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="0", max="9")
     * @Assert\Regex(pattern="/^#[0-9a-f]{6,8}$/i")
     *
     * @Serializer\Expose
     */
    protected ?string $color = null;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=30)
     *
     * @Serializer\Expose
     */
    protected ?string $icon = null;

    /**
     * @ORM\Column(type="integer")
     *
     * @Gedmo\SortablePosition
     *
     * @Assert\Type(type="integer")
     *
     * @Serializer\Expose
     */
    protected ?int $position = -1;

    /**
     * @ORM\Column(type="boolean", name="is_default", options={"default": 0})
     *
     * @Assert\Type(type="boolean")
     *
     * @Serializer\Expose
     */
    private bool $default = false;

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setDefault(bool $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
