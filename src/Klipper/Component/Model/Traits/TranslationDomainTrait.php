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

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of translation domain model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait TranslationDomainTrait
{
    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     *
     * @Serializer\Expose
     */
    protected $translationDomain;

    /**
     * {@inheritdoc}
     */
    public function setTranslationDomain(?string $translationDomain): self
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }
}
