<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Contracts\Model;

/**
 * Interface to manage translation domain.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface TranslationDomainInterface
{
    /**
     * Set the translation domain.
     *
     * @param null|string $translationDomain The translation domain
     *
     * @return static
     */
    public function setTranslationDomain(?string $translationDomain);

    /**
     * Get the translation domain.
     */
    public function getTranslationDomain(): ?string;
}
