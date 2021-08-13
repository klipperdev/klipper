<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Doctrine;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DeleteContentConfig
{
    private string $uploaderName;

    private string $classname;

    private string $propertyPath;

    public function __construct(string $uploaderName, string $classname, string $propertyPath)
    {
        $this->uploaderName = $uploaderName;
        $this->classname = $classname;
        $this->propertyPath = $propertyPath;
    }

    public function getUploaderName(): string
    {
        return $this->uploaderName;
    }

    public function getClassname(): string
    {
        return $this->classname;
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }
}
