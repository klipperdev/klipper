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
 * Interface of add file path.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface FilePathInterface
{
    /**
     * Check if the file path exist.
     */
    public function hasFile(): bool;

    /**
     * Get the extension of the file path.
     */
    public function getFileExtension(): ?string;

    /**
     * Set the file path.
     *
     * @param null|string $filePath The file path
     *
     * @return static
     */
    public function setFilePath(?string $filePath);

    /**
     * Get the file path.
     */
    public function getFilePath(): ?string;
}
