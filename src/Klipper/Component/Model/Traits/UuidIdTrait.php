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
 * Trait of id.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait UuidIdTrait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     * @Serializer\Expose
     * @Serializer\ReadOnlyProperty
     */
    protected ?string $id = null;

    public function getId(): ?string
    {
        return $this->id;
    }
}
