<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Annotation;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface AnnotationInterface
{
    /**
     * Returns the alias name for an annotated configuration.
     */
    public function getAliasName(): ?string;

    /**
     * Returns whether multiple annotations of this type are allowed.
     */
    public function allowArray(): bool;
}
