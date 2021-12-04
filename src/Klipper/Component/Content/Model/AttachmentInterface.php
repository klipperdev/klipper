<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Model;

use Klipper\Contracts\Model\FilePathInterface;
use Klipper\Contracts\Model\NameableInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface AttachmentInterface extends FilePathInterface, NameableInterface
{
    /**
     * @return static
     */
    public function setMainAttachment(object $mainAttachment);

    public function getMainAttachment(): ?object;

    public function getExtension(): ?string;

    /**
     * @return static
     */
    public function setExtension(?string $extension);

    public function getTypeMime(): ?string;

    /**
     * @return static
     */
    public function setTypeMime(?string $typeMime);

    public function getSize(): ?int;

    /**
     * @return static
     */
    public function setSize(?int $size);

    public function getWidth(): ?int;

    /**
     * @return static
     */
    public function setWidth(?int $width);

    public function getHeight(): ?int;

    /**
     * @return static
     */
    public function setHeight(?int $height);

    public function isImage(): bool;

    /**
     * @return static
     */
    public function setImage(bool $image);

    public function getFileExtension(): ?string;

    public function getBasename(): string;
}
