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
use Ramsey\Uuid\Uuid;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UuidNamer implements NamerInterface
{
    public function getName(): string
    {
        return 'uuid';
    }

    /**
     * @throws
     */
    public function name(FileInterface $file): string
    {
        return sprintf('%s.%s', Uuid::uuid4()->toString(), $file->getExtension());
    }
}
