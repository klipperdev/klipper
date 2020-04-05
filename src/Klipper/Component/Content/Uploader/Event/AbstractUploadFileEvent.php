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
abstract class AbstractUploadFileEvent extends AbstractUploadEvent
{
    /**
     * @var FileInterface
     */
    private $file;

    /**
     * Constructor.
     *
     * @param UploaderConfigurationInterface $config  The uploader configuration
     * @param Request                        $request The request
     * @param FileInterface                  $file    The file
     */
    public function __construct(UploaderConfigurationInterface $config, Request $request, FileInterface $file)
    {
        parent::__construct($config, $request);

        $this->file = $file;
    }

    /**
     * Get the file.
     */
    public function getFile(): FileInterface
    {
        return $this->file;
    }
}
