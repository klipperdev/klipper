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

use Doctrine\Common\Collections\Collection;
use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;

/**
 * Interface for portal featureable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PortalFeatureableInterface
{
    /**
     * @return ChoiceInterface[]
     */
    public function getPortalFeatures(): Collection;

    /**
     * @return string[]
     */
    public function getPortalFeatureValues(): array;

    public function hasPortalFeature(string $name): bool;
}
