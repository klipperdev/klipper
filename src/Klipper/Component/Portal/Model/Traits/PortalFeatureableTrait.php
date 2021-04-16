<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Model\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;
use Klipper\Component\DoctrineChoice\Validator\Constraints\EntityDoctrineChoice;

/**
 * Trait for portal featureable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait PortalFeatureableTrait
{
    /**
     * @var ChoiceInterface[]|Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity="Klipper\Component\DoctrineChoice\Model\ChoiceInterface",
     *     fetch="EAGER",
     *     cascade={"persist"}
     * )
     * @ORM\JoinTable(
     *     name="portal_feature",
     *     joinColumns={
     *         @ORM\JoinColumn(onDelete="CASCADE")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(onDelete="CASCADE", name="choice_id")
     *     }
     * )
     *
     * @EntityDoctrineChoice("portal_feature", multiple=true)
     *
     * @Serializer\Expose
     */
    private ?Collection $portalFeatures = null;

    /**
     * @return ChoiceInterface[]
     */
    public function getPortalFeatures(): Collection
    {
        return $this->portalFeatures ?: $this->portalFeatures = new ArrayCollection();
    }

    public function getPortalFeatureValues(): array
    {
        $values = [];

        foreach ($this->getPortalFeatures() as $feature) {
            $values[] = $feature->getValue();
        }

        return $values;
    }

    public function hasPortalFeature(string $name): bool
    {
        return \in_array($name, $this->getPortalFeatureValues(), true);
    }
}
