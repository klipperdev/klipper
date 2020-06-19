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
use Klipper\Component\Content\Uploader\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface UploaderInterface
{
    /**
     * Add the uploader.
     *
     * @param UploaderConfigurationInterface $uploader The uploader configuration
     *
     * @return static
     */
    public function add(UploaderConfigurationInterface $uploader);

    /**
     * Remove the uploader.
     *
     * @param string $uploader The uploader name
     *
     * @return static
     */
    public function remove(string $uploader);

    /**
     * Get the configuration of uploader.
     *
     * @param string $uploader The uploader name
     */
    public function has(string $uploader): bool;

    /**
     * Get the configuration of uploader.
     *
     * @param string $uploader The uploader name
     *
     * @throws InvalidArgumentException
     */
    public function get(string $uploader): UploaderConfigurationInterface;

    /**
     * Get all configurations of uploader.
     *
     * @return UploaderConfigurationInterface[]
     */
    public function all(): array;

    /**
     * Add the adapter.
     *
     * @param AdapterInterface $adapter The adapter
     *
     * @return static
     */
    public function addAdapter(AdapterInterface $adapter);

    /**
     * Upload a file.
     *
     * @param string     $uploader The uploader name
     * @param null|mixed $payload  The payload
     */
    public function upload(string $uploader, $payload = null): Response;
}
