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
 * Trait of externalable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait ExternalableTrait
{
    /**
     * @var array
     *
     * @ORM\Column(type="json")
     *
     * @Serializer\Expose
     */
    protected $externalIds = [];

    /**
     * {@inheritdoc}
     */
    public function getExternalIds(): array
    {
        return $this->externalIds ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function hasExternalId(string $service): bool
    {
        return isset($this->externalIds[$service]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalId(string $service): ?string
    {
        return $this->externalIds[$service] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalIds(array $serviceIds): void
    {
        $this->externalIds = [];

        foreach ($serviceIds as $service => $id) {
            $this->externalIds[$service] = (string) $id;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addExternalId(string $service, string $id): void
    {
        $this->externalIds[$service] = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function removeExternalId(string $service): void
    {
        unset($this->externalIds[$service]);
    }
}