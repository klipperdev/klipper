<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\ListView\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\Model\Traits\LabelableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of list view model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait ListViewTrait
{
    use LabelableTrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     */
    protected ?string $type = null;

    /**
     * @ORM\Column(type="json", nullable=true)
     *
     * @Assert\Json
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     */
    protected ?array $filters = null;

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setFilters(?array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function getFilters(): ?array
    {
        return $this->filters;
    }
}
