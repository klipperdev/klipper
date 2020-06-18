<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Uploader;

use Klipper\Component\Content\Uploader\Adapter\AdapterInterface;
use Klipper\Component\Content\Uploader\Event\PostUploadEvent;
use Klipper\Component\Content\Uploader\Event\PreUploadEvent;
use Klipper\Component\Content\Uploader\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Uploader implements UploaderInterface
{
    private EventDispatcherInterface $dispatcher;

    private RequestStack $requestStack;

    /**
     * @var UploaderConfigurationInterface[]
     */
    private array $uploaders = [];

    /**
     * @var AdapterInterface[]
     */
    private array $adapters = [];

    /**
     * @param EventDispatcherInterface         $dispatcher   The event dispatcher
     * @param RequestStack                     $requestStack The request stack
     * @param UploaderConfigurationInterface[] $uploaders    The uploaders
     * @param AdapterInterface[]               $adapters     The adapters
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        RequestStack $requestStack,
        array $uploaders = [],
        array $adapters = []
    ) {
        $this->dispatcher = $dispatcher;
        $this->requestStack = $requestStack;

        foreach ($uploaders as $uploader) {
            $this->add($uploader);
        }

        foreach ($adapters as $adapter) {
            $this->addAdapter($adapter);
        }
    }

    public function add(UploaderConfigurationInterface $uploader): self
    {
        $this->uploaders[$uploader->getName()] = $uploader;

        return $this;
    }

    public function remove(string $uploader): self
    {
        unset($this->uploaders[$uploader]);

        return $this;
    }

    public function has(string $uploader): bool
    {
        return isset($this->uploaders[$uploader]);
    }

    public function get(string $uploader): UploaderConfigurationInterface
    {
        if (!isset($this->uploaders[$uploader])) {
            throw new InvalidArgumentException(sprintf('The "%s" uploader does not exist', $uploader));
        }

        return $this->uploaders[$uploader];
    }

    public function all(): array
    {
        return $this->uploaders;
    }

    public function addAdapter(AdapterInterface $adapter): self
    {
        $this->adapters[] = $adapter;

        return $this;
    }

    public function upload(string $uploader): Response
    {
        $config = $this->get($uploader);
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            throw new InvalidArgumentException('The request is required to upload the file');
        }

        foreach ($this->adapters as $adapter) {
            if ($adapter->supports($request, $config)) {
                $this->dispatcher->dispatch(new PreUploadEvent($config, $request));
                $response = $adapter->upload($request, $config);
                $this->dispatcher->dispatch(new PostUploadEvent($config, $request, $response));

                return $response;
            }
        }

        throw new BadRequestHttpException('No upload adapter is compatible with the request');
    }
}
