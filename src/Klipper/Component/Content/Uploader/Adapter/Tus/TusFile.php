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
    /**
     * @var File
     */
    private $file;

    /**
     * Constructor.
     *
     * @param File $file The TUS file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): int
    {
        return $this->file->getFileSize();
    }

    /**
     * {@inheritdoc}
     */
    public function getPathname(): string
    {
        return pathinfo($this->file->getFilePath(), PATHINFO_FILENAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return $this->file->getFilePath();
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getBasename()
    {
        return pathinfo($this->file->getFilePath(), PATHINFO_BASENAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
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
