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
use Klipper\Component\DoctrineExtensionsExtra\Model\Traits\TranslatableTrait as BaseTranslatableTrait;

/**
 * Translatable trait.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait TranslatableTrait
{
    use BaseTranslatableTrait;

    /**
     * @var string[]
     *
     * @ORM\Column(type="json", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\ReadOnly
     */
    protected ?array $availableLocales = null;
}
