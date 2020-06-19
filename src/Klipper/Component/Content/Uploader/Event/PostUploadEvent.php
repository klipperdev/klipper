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
    private Response $response;

    /**
     * @param null|mixed $payload
     */
    public function __construct(
        UploaderConfigurationInterface $config,
        Request $request,
        Response $response,
        $payload
    ) {
        parent::__construct($config, $request, $payload);

        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
