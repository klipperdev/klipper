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
     * @see \SplFileInfo::getSize()
     *
     * @return int
     */
    public function getSize();

    /**
     * Returns the path of the file.
     *
     * @see \SplFileInfo::getPathname()
     *
     * @return string
     */
    public function getPathname();

    /**
     * Return the path of the file without the filename.
     *
     * @see \SplFileInfo::getPath()
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
     * @see \SplFileInfo::getBasename()
     *
     * @return null|string
     */
    public function getBasename();

    /**
     * Returns the guessed extension of the file.
     *
     * @see \SplFileInfo::getExtension()
     *
     * @return string
     */
    public function getExtension();
}
