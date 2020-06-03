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
class UploaderConfiguration implements UploaderConfigurationInterface
{
    private string $name;

    private string $path;

    private int $maxSize;

    /**
     * @var string[]
     */
    private array $allowedTypeMimes;

    private ?string $namer;

    /**
     * @param string      $name             The uploader name
     * @param string      $path             The path of the upload directory
     * @param int         $maxSize          The max size of the uploaded file
     * @param string[]    $allowedTypeMimes The allowed type mimes
     * @param null|string $namer            The namer file name
     */
    public function __construct(string $name, string $path, int $maxSize = 0, array $allowedTypeMimes = [], ?string $namer = null)
    {
        $this->name = $name;
        $this->path = $path;
        $this->maxSize = $maxSize;
        $this->allowedTypeMimes = $allowedTypeMimes;
        $this->namer = $namer;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMaxSize(): int
    {
        return $this->maxSize;
    }

    public function getAllowedTypeMimes(): array
    {
        return $this->allowedTypeMimes;
    }

    public function getNamer(): ?string
    {
        return $this->namer;
    }
}
