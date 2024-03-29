<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\View;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ViewChoice implements ViewChoiceInterface
{
    protected string $name;

    protected array $identifiers;

    protected ?string $placeholder;

    /**
     * @param string      $name        The choice name
     * @param array       $identifiers The translated identifiers
     * @param null|string $placeholder The translated placeholder
     */
    public function __construct(string $name, array $identifiers, ?string $placeholder = null)
    {
        $this->name = $name;
        $this->identifiers = $identifiers;
        $this->placeholder = $placeholder;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }
}
