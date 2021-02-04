<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Config;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface UploaderNameConfigRegistryInterface
{
    /**
     * Add uploader name config.
     *
     * @param UploaderNameConfigInterface $config The uploader name config
     */
    public function addConfig(UploaderNameConfigInterface $config): void;

    /**
     * Get the uploader name by payload.
     *
     * @param mixed $payload The payload
     */
    public function getUploaderName($payload): ?string;
}
