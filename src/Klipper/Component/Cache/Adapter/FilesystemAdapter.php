<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Cache\Adapter;

use Symfony\Component\Cache\Adapter\FilesystemAdapter as BaseFilesystemAdapter;

/**
 * Filesystem Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class FilesystemAdapter extends BaseFilesystemAdapter implements AdapterInterface
{
    use AdapterTrait;

    /**
     * {@inheritdoc}
     */
    protected function doClearByPrefix(string $namespace, string $prefix): bool
    {
        $ok = true;
        $directory = AdapterUtil::getPropertyValue($this, 'directory');

        /** @var \SplFileInfo $file */
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(
            $directory,
            \FilesystemIterator::SKIP_DOTS
        )) as $file) {
            $this->doClearFile($ok, $file, $prefix);
        }

        return $ok;
    }

    /**
     * Action to clear all items in file starting with the prefix.
     *
     * @param bool         $ok     The delete status
     * @param \SplFileInfo $file   The spl file info
     * @param string       $prefix The prefix
     *
     * @throws
     */
    private function doClearFile(bool &$ok, \SplFileInfo $file, string $prefix): void
    {
        $keys = [];

        if ($file->isFile()) {
            $key = $this->getFileKey($file);

            if (null !== $key && ('' === $prefix || 0 === strpos($key, $prefix))) {
                $keys[] = $key;
            }
        }

        $ok = ($file->isDir() || $this->deleteItems($keys) || !file_exists($file)) && $ok;
    }

    /**
     * Get the key of file.
     *
     * @param \SplFileInfo $file The spl file info
     */
    private function getFileKey(\SplFileInfo $file): ?string
    {
        $key = null;

        if ($h = @fopen($file, 'r')) {
            rawurldecode(rtrim(fgets($h)));
            $value = stream_get_contents($h);
            fclose($h);
            $key = substr($value, 0, strpos($value, "\n"));
        }

        return $key;
    }
}
