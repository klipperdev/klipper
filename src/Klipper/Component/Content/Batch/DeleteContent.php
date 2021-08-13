<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Batch;

use Klipper\Component\Batch\JobResult;
use Klipper\Component\Batch\JobResultInterface;
use Klipper\Component\Content\ContentManagerInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DeleteContent
{
    private ContentManagerInterface $contentManager;

    public function __construct(ContentManagerInterface $contentManager)
    {
        $this->contentManager = $contentManager;
    }

    /**
     * Remove the file.
     *
     * @param string          $uploaderName The name of the uploader used
     * @param string|string[] $path         The one path or multiple paths
     */
    public function remove(string $uploaderName, string $path): JobResultInterface
    {
        return $this->removes($uploaderName, [$path]);
    }

    /**
     * Remove the file.
     *
     * @param string   $uploaderName The name of the uploader used
     * @param string[] $paths        The multiple paths
     */
    public function removes(string $uploaderName, array $paths): JobResultInterface
    {
        $this->contentManager->remove($uploaderName, $paths);

        return new JobResult();
    }
}
