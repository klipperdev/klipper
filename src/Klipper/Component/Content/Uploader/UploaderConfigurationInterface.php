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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface UploaderConfigurationInterface
{
    /**
     * Get the name of the uploader.
     */
    public function getName(): string;

    /**
     * Get the path of the uploader directory.
     */
    public function getPath(): string;

    /**
     * Get the max size of the uploaded file. The 0 value allows to have no limit.
     */
    public function getMaxSize(): int;

    /**
     * Get the list of allowed type mimes.
     *
     * @return string[]
     */
    public function getAllowedTypeMimes(): array;

    /**
     * Get the namer file name.
     */
    public function getNamer(): ?string;

    /**
     * Get the classname of attachment model.
     */
    public function getAttachmentClass(): ?string;

    /**
     * Check if the uploader configuration is for the attachment model.
     */
    public function isAttachment(): bool;
}
