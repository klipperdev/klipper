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
use Symfony\Component\HttpFoundation\Response;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PostUploadEvent extends AbstractUploadEvent
{
    /**
     * @var Response
     */
    private $response;

    /**
     * {@inheritdoc}
     */
    public function __construct(UploaderConfigurationInterface $config, Request $request, Response $response)
    {
        parent::__construct($config, $request);

        $this->response = $response;
    }

    /**
     * Get the response.
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
