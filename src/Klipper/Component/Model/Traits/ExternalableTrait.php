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
     * @ORM\Column(type="json")
     *
     * @Serializer\Expose
     */
    protected ?array $externalIds = [];

    public function getExternalIds(): array
    {
        return $this->externalIds ?? [];
    }

    public function hasExternalId(string $service): bool
    {
        return isset($this->externalIds[$service]);
    }

    public function getExternalId(string $service): ?string
    {
        return $this->externalIds[$service] ?? null;
    }

    public function setExternalIds(array $serviceIds): void
    {
        foreach ($serviceIds as $service => $id) {
            if (empty($id)) {
                $this->removeExternalId($service);
            } else {
                $this->addExternalId($service, $id);
            }
        }
    }

    public function addExternalId(string $service, string $id): void
    {
        $this->externalIds[$service] = $id;
    }

    public function removeExternalId(string $service): void
    {
        unset($this->externalIds[$service]);
    }

    public function clearExternalIds(): void
    {
        $this->externalIds = [];
    }
}
