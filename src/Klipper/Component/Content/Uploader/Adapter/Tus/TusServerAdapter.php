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

use Klipper\Component\Content\Uploader\Adapter\AdapterInterface;
use Klipper\Component\Content\Uploader\Namer\NamerManagerInterface;
use Klipper\Component\Content\Uploader\UploaderConfigurationInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use TusPhp\Tus\Server;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TusServerAdapter implements AdapterInterface
{
    private Server $server;

    private EventDispatcherInterface $dispatcher;

    private NamerManagerInterface $namerManager;

    /**
     * @param Server                   $server       The Tus server
     * @param EventDispatcherInterface $dispatcher   The event dispatcher
     * @param NamerManagerInterface    $namerManager The namer manager
     */
    public function __construct(
        Server $server,
        EventDispatcherInterface $dispatcher,
        NamerManagerInterface $namerManager
    ) {
        $this->server = $server;
        $this->dispatcher = $dispatcher;
        $this->namerManager = $namerManager;
    }

    public function supports(Request $request, UploaderConfigurationInterface $uploader): bool
    {
        return $request->headers->has('Tus-Resumable');
    }

    /**
     * @throws
     */
    public function upload(Request $request, UploaderConfigurationInterface $uploader): Response
    {
        $subscriber = new TusServerEventSubscriber($this->dispatcher, $this->namerManager, $uploader, $request);
        $prevApiPath = $this->server->getApiPath();
        $prevMaxUploadSize = $this->server->getMaxUploadSize();
        $exception = null;
        $res = null;

        $uriExp = explode('/', $request->getRequestUri());

        if (Uuid::isValid($uriExp[\count($uriExp) - 1])) {
            array_pop($uriExp);
        }

        $this->server->setApiPath(implode('/', $uriExp));
        $this->server->setMaxUploadSize($uploader->getMaxSize());

        try {
            $this->server->event()->addSubscriber($subscriber);
            $res = $this->server->serve();
        } catch (\Throwable $e) {
            $exception = $e;
        } finally {
            $this->server->setApiPath($prevApiPath);
            $this->server->setMaxUploadSize($prevMaxUploadSize);
            $this->server->event()->removeSubscriber($subscriber);
        }

        if ($res->getStatusCode() >= Response::HTTP_BAD_REQUEST) {
            $exception = new HttpException($res->getStatusCode());
        }

        if (null !== $exception) {
            throw $exception;
        }

        return $res;
    }
}
