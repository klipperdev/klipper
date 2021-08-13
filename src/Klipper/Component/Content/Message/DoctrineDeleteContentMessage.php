<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Message;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DoctrineDeleteContentMessage
{
    private string $uploaderName;

    /**
     * @var string[]
     */
    private array $paths;

    /**
     * @param string[] $paths
     */
    public function __construct(string $uploaderName, array $paths)
    {
        $this->uploaderName = $uploaderName;
        $this->paths = $paths;
    }

    public function getUploaderName(): string
    {
        return $this->uploaderName;
    }

    /**
     * @return string[]
     */
    public function getPaths(): array
    {
        return $this->paths;
    }
}
