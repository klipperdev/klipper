<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Uploader\Namer;

use Klipper\Component\Content\Uploader\File\FileInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UniqidNamer implements NamerInterface
{
    public function getName(): string
    {
        return 'uniqid';
    }

    public function name(FileInterface $file): string
    {
        return sprintf(
            '%s.%s',
            str_replace('.', '-', uniqid('', true)),
            $file->getExtension()
        );
    }
}
