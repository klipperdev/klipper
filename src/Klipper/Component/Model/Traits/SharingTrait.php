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
use Klipper\Component\Security\Model\Traits\SharingTrait as BaseSharingTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of role model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait SharingTrait
{
    use BaseSharingTrait;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=244)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=244)
     * @Assert\NotBlank
     */
    protected $subjectClass;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=36)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=36)
     * @Assert\NotBlank
     */
    protected $subjectId;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=244)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=244)
     * @Assert\NotBlank
     */
    protected $identityClass;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=244)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=244)
     * @Assert\NotBlank
     */
    protected $identityName;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type("boolean")
     */
    protected $enabled = true;

    /**
     * @var null|\DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type(type="datetime")
     */
    protected $startedAt;

    /**
     * @var null|\DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type(type="datetime")
     */
    protected $endedAt;
}
