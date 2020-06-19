<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Uploader\Adapter;

use Klipper\Component\Content\Uploader\UploaderConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface AdapterInterface
{
    /**
     * Check if the adapter is supported.
     *
     * @param Request                        $request  The request
     * @param UploaderConfigurationInterface $uploader The uploader configuration
     */
    public function supports(Request $request, UploaderConfigurationInterface $uploader): bool;

    /**
     * Upload a file.
     *
     * @param Request                        $request  The request
     * @param UploaderConfigurationInterface $uploader The uploader configuration
     * @param null|mixed                     $payload  The payload
     */
    public function upload(Request $request, UploaderConfigurationInterface $uploader, $payload = null): Response;
}
