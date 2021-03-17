<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Contracts\Model;

/**
 * Interface of external id model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface SingleExternalableInterface
{
    /**
     * Set the external id.
     *
     * @param null|string $externalId The external id
     *
     * @return static
     */
    public function setExternalId(?string $externalId);

    /**
     * Get the external id.
     */
    public function getExternalId(): ?string;
}
