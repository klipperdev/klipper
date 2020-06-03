<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Uploader\Adapter\Tus;

use Klipper\Component\Content\Uploader\File\FileInterface;
use TusPhp\File;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TusFile implements FileInterface
{
    private File $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function getSize(): int
    {
        return $this->file->getFileSize();
    }

    public function getPathname(): string
    {
        return pathinfo($this->file->getFilePath(), PATHINFO_FILENAME);
    }

    public function getPath(): string
    {
        return $this->file->getFilePath();
    }

    public function getMimeType(): ?string
    {
        return null;
    }

    public function getBasename(): string
    {
        return pathinfo($this->file->getFilePath(), PATHINFO_BASENAME);
    }

    public function getExtension(): string
    {
        return pathinfo($this->file->getFilePath(), PATHINFO_EXTENSION);
    }

    /**
     * Get the TUS file.
     */
    public function getFile(): File
    {
        return $this->file;
    }
}
