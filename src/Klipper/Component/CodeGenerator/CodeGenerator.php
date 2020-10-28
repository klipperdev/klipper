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
class CodeGenerator implements CodeGeneratorInterface
{
    public const DEFAULT_CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private int $minLength;

    private int $maxLength;

    private string $chars;

    public function __construct(int $minLength = 1, int $maxLength = 1, ?string $chars = null)
    {
        $this->minLength = max(1, $minLength);
        $this->maxLength = max($this->minLength, $maxLength);
        $this->chars = $chars ?? static::DEFAULT_CHARS;
    }

    /**
     * @throws
     */
    public function generate(): string
    {
        $code = '';
        $length = random_int($this->minLength, $this->maxLength);

        for ($i = 0; $i < $length; ++$i) {
            $code .= $this->chars[random_int(0, \strlen($this->chars) - 1)];
        }

        return $code;
    }
}
