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

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class FilesystemFile extends UploadedFile implements FileInterface
{
    public function __construct(\SplFileInfo $file)
    {
        if ($file instanceof UploadedFile) {
            parent::__construct($file->getPathname(), $file->getClientOriginalName(), $file->getClientMimeType(), $file->getError(), true);
        } elseif ($file instanceof File) {
            parent::__construct($file->getPathname(), $file->getBasename(), $file->getMimeType(), 0, true);
        } else {
            parent::__construct($file->getPathname(), $file->getBasename(), mime_content_type($file->getPath()), 0, true);
        }
    }

    public function getExtension(): string
    {
        return $this->getClientOriginalExtension();
    }
}
