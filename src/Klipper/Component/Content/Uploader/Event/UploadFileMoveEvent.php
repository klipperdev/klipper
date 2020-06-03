<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Uploader\Event;

use Klipper\Component\Content\Uploader\File\FileInterface;
use Klipper\Component\Content\Uploader\UploaderConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UploadFileMoveEvent extends AbstractUploadFileEvent
{
    private string $name;

    /**
     * @param string $name The new file name
     */
    public function __construct(
        UploaderConfigurationInterface $config,
        Request $request,
        FileInterface $file,
        string $name
    ) {
        parent::__construct($config, $request, $file);

        $this->name = $name;
    }

    public function getFileName(): string
    {
        return $this->name;
    }
}
