<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityExtra\Doctrine\Filter\Listener;

use Klipper\Component\SecurityExtra\Doctrine\Filter\OrganizationFilter;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OrganizationFilterSubscriber extends AbstractTokenUserFilterSubscriber
{
    /**
     * {@inheritdoc}
     */
    protected function supports(): string
    {
        return OrganizationFilter::class;
    }
}