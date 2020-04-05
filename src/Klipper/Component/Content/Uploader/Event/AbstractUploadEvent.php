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

use Klipper\Component\Content\Uploader\UploaderConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractUploadEvent extends Event
{
    /**
     * @var UploaderConfigurationInterface
     */
    private $config;

    /**
     * @var Request
     */
    private $request;

    /**
     * Constructor.
     *
     * @param UploaderConfigurationInterface $config  The uploader configuration
     * @param Request                        $request The request
     */
    public function __construct(UploaderConfigurationInterface $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * Get the uploader configuration.
     */
    public function getConfig(): UploaderConfigurationInterface
    {
        return $this->config;
    }

    /**
     * Get the request.
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
