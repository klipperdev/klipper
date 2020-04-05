<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\SecurityExtraBundle\DependencyInjection\Compiler;

use Klipper\Bundle\SecurityBundle\DependencyInjection\Compiler\AbstractLoaderPass;

/**
 * Adds all services with the tags "klipper_security_extra.organizational_filter.excluded_classes_loader" as arguments
 * of the "klipper_security_extra.organizational_filter.excluded_classes_resolver" service.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OrganizationalFilterExcludedClassesLoaderPass extends AbstractLoaderPass
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'klipper_security_extra.organizational_filter.excluded_classes_resolver',
            'klipper_security_extra.organizational_filter.excluded_classes_loader'
        );
    }
}