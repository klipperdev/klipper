<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\User\Model\Traits;

use Klipper\Component\Model\Traits\ImagePathInterface;

/**
 * Profileable interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ProfileableInterface extends ImagePathInterface
{
    /**
     * @return static
     */
    public function setFirstName(?string $firstName);

    public function getFirstName(): ?string;

    /**
     * @return static
     */
    public function setLastName(?string $lastName);

    public function getLastName(): ?string;

    /**
     * @return static
     */
    public function setInitial(?string $initial);

    public function getInitial(): ?string;

    /**
     * @param string $format The format
     */
    public function getFullName(string $format = '{firstName} {lastName}'): ?string;
}
