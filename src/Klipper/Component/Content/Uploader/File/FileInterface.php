<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Uploader\File;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface FileInterface
{
    /**
     * Returns the size of the file.
     *
     * @return int
     */
    public function getSize();

    /**
     * Returns the path of the file.
     *
     * @return string
     */
    public function getPathname();

    /**
     * Return the path of the file without the filename.
     *
     * @return string
     */
    public function getPath();

    /**
     * Returns the guessed mime type of the file.
     *
     * @return null|string
     */
    public function getMimeType();

    /**
     * Returns the basename of the file.
     *
     * @return string
     */
    public function getBasename();

    /**
     * Returns the guessed extension of the file.
     *
     * @return string
     */
    public function getExtension();
}
