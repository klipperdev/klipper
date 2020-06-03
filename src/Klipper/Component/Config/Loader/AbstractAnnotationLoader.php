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

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Config\Loader\Loader;

/**
 * The abstract class of annotation loader.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractAnnotationLoader extends Loader
{
    protected Reader $reader;

    protected ClassFinder $classFinder;

    /**
     * @param Reader           $reader      The annotation reader
     * @param null|ClassFinder $classFinder The class finder
     */
    public function __construct(Reader $reader, ?ClassFinder $classFinder = null)
    {
        $this->reader = $reader;
        $this->classFinder = $classFinder ?? new ClassFinder();
    }
}
