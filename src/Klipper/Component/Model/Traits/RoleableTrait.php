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
use Klipper\Component\Security\Model\Traits\RoleableTrait as BaseRoleableTrait;

/**
 * Trait of roleable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait RoleableTrait
{
    use BaseRoleableTrait;

    /**
     * @var string[]
     *
     * @ORM\Column(type="json")
     * @Serializer\Expose
     */
    protected array $roles = [];
}
