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
class UrlSafeNamer implements NamerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'url_safe';
    }

    /**
     * {@inheritdoc}
     *
     * @throws
     */
    public function name(FileInterface $file): string
    {
        $bytes = random_bytes(256 / 8);

        return sprintf('%s.%s', rtrim(strtr(base64_encode($bytes), '+/', '-_'), '='), $file->getExtension());
    }
}
