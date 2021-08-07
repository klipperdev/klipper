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
use Klipper\Component\DoctrineExtensionsExtra\Model\Traits\TimestampableTrait as BaseTimestampableTrait;

/**
 * Timestampable trait.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait TimestampableTrait
{
    use BaseTimestampableTrait;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @Serializer\Expose
     * @Serializer\ReadOnlyProperty
     */
    protected ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @Serializer\Expose
     * @Serializer\ReadOnlyProperty
     */
    protected ?\DateTimeInterface $updatedAt = null;
}
