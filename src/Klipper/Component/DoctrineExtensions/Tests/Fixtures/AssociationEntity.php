<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtensions\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fixture.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @ORM\Entity
 */
class AssociationEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="SingleIntIdEntity")
     */
    public ?SingleIntIdEntity $single = null;

    /**
     * @ORM\ManyToOne(targetEntity="CompositeIntIdEntity")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="composite_id1", referencedColumnName="id1"),
     *     @ORM\JoinColumn(name="composite_id2", referencedColumnName="id2")
     * })
     */
    public ?CompositeIntIdEntity $composite = null;

    /**
     * @ORM\Id @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;
}
