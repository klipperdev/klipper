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

use Symfony\Component\HttpFoundation\Response;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UploadFileCompletedEvent extends AbstractUploadFileEvent
{
    private array $responsePayload = [];

    private ?Response $response = null;

    public function getResponsePayload(): array
    {
        return $this->responsePayload;
    }

    public function setResponsePayload(array $responsePayload): static
    {
        $this->responsePayload = $responsePayload;

        return $this;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): static
    {
        $this->response = $response;

        return $this;
    }
}
