<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\CodeGenerator;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface CodeGeneratorInterface
{
    /**
     * Generate the short code.
     */
    public function generate(): string;
}
