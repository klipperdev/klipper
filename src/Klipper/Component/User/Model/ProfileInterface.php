<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\User\Model;

use Klipper\Component\Model\Traits\IdInterface;
use Klipper\Component\Model\Traits\ImagePathInterface;
use Klipper\Component\Model\Traits\TimestampableInterface;
use Klipper\Component\SecurityExtra\Model\Traits\UserableEditableInterface;

/**
 * Profile interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ProfileInterface extends
    IdInterface,
    UserableEditableInterface,
    TimestampableInterface,
    ImagePathInterface
{
    public function getUsername(): ?string;

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
     * @param string $format The format
     */
    public function getFullName(string $format = '{firstName} {lastName}'): ?string;

    public function getInitial(): string;
}
