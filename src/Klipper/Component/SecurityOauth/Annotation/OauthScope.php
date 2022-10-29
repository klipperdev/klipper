<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Annotation;

use Klipper\Component\Config\Annotation\AbstractAnnotation;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @Annotation
 *
 * @Target({"CLASS", "METHOD"})
 */
class OauthScope extends AbstractAnnotation
{
    /**
     * @Required
     */
    protected array $scope = [];

    protected bool $allRequired = false;

    public function getAliasName(): ?string
    {
        return 'required_oauth_scopes';
    }

    public function allowArray(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function getScope(): array
    {
        return $this->scope;
    }

    /**
     * @param string|string[] $scope
     */
    public function setScope($scope): void
    {
        $this->scope = (array) $scope;
    }

    public function isAllRequired(): bool
    {
        return $this->allRequired;
    }

    public function setAllRequired(bool $required): void
    {
        $this->allRequired = $required;
    }

    /**
     * @param string|string[] $value
     */
    public function setValue($value): void
    {
        $this->setScope($value);
    }
}
