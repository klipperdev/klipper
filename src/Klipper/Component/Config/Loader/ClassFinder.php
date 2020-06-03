<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Loader;

use Symfony\Component\Finder\Finder;

/**
 * The class finder.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ClassFinder
{
    /**
     * @var string[]
     */
    private array $includePaths;

    /**
     * @var string[]
     */
    private array $excludePaths;

    /**
     * @var null|string[]
     */
    private ?array $cache = null;

    /**
     * @param string[] $includePaths The include paths
     * @param string[] $excludePaths The exclude paths
     */
    public function __construct(array $includePaths = [], array $excludePaths = [])
    {
        $this->includePaths = $includePaths;
        $this->excludePaths = $excludePaths;
    }

    /**
     * Find the class names in the directories.
     *
     * @return string[]
     */
    public function findClasses(array $paths = []): array
    {
        if (null === $this->cache) {
            $finder = Finder::create()
                ->ignoreVCS(true)
                ->ignoreDotFiles(true)
                ->in(array_unique(array_merge($this->includePaths, $paths)))
                ->exclude($this->excludePaths)
                ->name('*.php')
            ;

            foreach ($finder->getIterator() as $file) {
                $this->cache[] = PhpParser::extractClasses($file->getPathname());
            }

            $this->cache = \count($this->cache) > 0 ? array_merge(...$this->cache) : $this->cache;
        }

        return $this->cache;
    }
}
