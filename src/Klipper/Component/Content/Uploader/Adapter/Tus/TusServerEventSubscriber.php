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

use Klipper\Component\Content\Uploader\Event\UploadFileCompletedEvent;
use Klipper\Component\Content\Uploader\Event\UploadFileCompleteEvent;
use Klipper\Component\Content\Uploader\Event\UploadFileCreatedEvent;
use Klipper\Component\Content\Uploader\Event\UploadFileMergedEvent;
use Klipper\Component\Content\Uploader\Event\UploadFileMoveEvent;
use Klipper\Component\Content\Uploader\Event\UploadFileProgressEvent;
use Klipper\Component\Content\Uploader\Namer\NamerManagerInterface;
use Klipper\Component\Content\Uploader\UploaderConfigurationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use TusPhp\Events\TusEvent;
use TusPhp\Events\UploadComplete;
use TusPhp\Events\UploadCreated;
use TusPhp\Events\UploadMerged;
use TusPhp\Events\UploadProgress;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TusServerEventSubscriber implements EventSubscriberInterface
{
    private EventDispatcherInterface $dispatcher;

    private NamerManagerInterface $namerManager;

    private UploaderConfigurationInterface $config;

    private Request $request;

    /**
     * @param EventDispatcherInterface       $dispatcher   The event dispatcher
     * @param NamerManagerInterface          $namerManager The namer manager
     * @param UploaderConfigurationInterface $config       The uploader configuration
     * @param Request                        $request      The request
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        NamerManagerInterface $namerManager,
        UploaderConfigurationInterface $config,
        Request $request
    ) {
        $this->dispatcher = $dispatcher;
        $this->namerManager = $namerManager;
        $this->config = $config;
        $this->request = $request;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UploadCreated::NAME => [
                ['onUploadCreated', 0],
            ],
            UploadProgress::NAME => [
                ['onUploadProgress', 0],
            ],
            UploadMerged::NAME => [
                ['onUploadMerged', 0],
            ],
            UploadComplete::NAME => [
                ['onUploadComplete', 0],
            ],
        ];
    }

    public function onUploadCreated(UploadCreated $event): void
    {
        $this->dispatch($event, UploadFileCreatedEvent::class);
    }

    public function onUploadProgress(UploadProgress $event): void
    {
        $this->dispatch($event, UploadFileProgressEvent::class);
    }

    public function onUploadMerged(UploadMerged $event): void
    {
        $this->dispatch($event, UploadFileMergedEvent::class);
    }

    public function onUploadComplete(UploadComplete $event): void
    {
        $name = null; // TODO replace the request metadata name/filename on the creation

        $tusFile = $event->getFile();
        $this->dispatcher->dispatch(new UploadFileMoveEvent(
            $this->config,
            $this->request,
            new TusFile($tusFile),
            $name
        ));

        //TODO move temp dir to final dir

        $this->dispatch($event, UploadFileCompleteEvent::class);
        $this->dispatch($event, UploadFileCompletedEvent::class);
    }

    /**
     * Convert and dispatch the TUS events.
     *
     * @param TusEvent $event      The TUS event
     * @param string   $eventClass The class name of event
     */
    private function dispatch(TusEvent $event, string $eventClass): void
    {
        $tusFile = $event->getFile();
        $this->dispatcher->dispatch(new $eventClass(
            $this->config,
            $this->request,
            new TusFile($tusFile)
        ));
    }
}
