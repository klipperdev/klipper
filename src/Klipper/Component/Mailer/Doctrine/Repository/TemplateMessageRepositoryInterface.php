<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Doctrine\Repository;

use Doctrine\Persistence\ObjectRepository;
use Klipper\Component\Mailer\Model\TemplateMessageInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface TemplateMessageRepositoryInterface extends ObjectRepository
{
    /**
     * Find the template message by her name.
     *
     * @param string      $name   The template message name
     * @param null|string $type   The template type
     * @param null|string $locale The locale
     */
    public function findTemplate(string $name, ?string $type = null, ?string $locale = null): ?TemplateMessageInterface;
}
