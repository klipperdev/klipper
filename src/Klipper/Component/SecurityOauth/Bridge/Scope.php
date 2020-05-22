<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Bridge;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Scope implements ScopeEntityInterface
{
    use EntityTrait;

    public static array $scopes = [];

    public function __construct(string $name)
    {
        $this->setIdentifier($name);
    }

    public static function hasScope($id): bool
    {
        return '*' === $id || \array_key_exists($id, static::$scopes);
    }

    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}
