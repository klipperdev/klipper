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
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of sortable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait SortableTrait
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\SortablePosition
     * @Assert\Type(type="integer")
     * @Serializer\Expose
     */
    protected ?int $position = null;

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position ?? -1;
    }
}
