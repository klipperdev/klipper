<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Listener;

use Klipper\Component\Content\ContentManagerInterface;
use Klipper\Component\Resource\Domain\DomainManagerInterface;
use Klipper\Contracts\Model\ImagePathInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ImagePathUploadSubscriber extends ContentPathUploadSubscriber
{
    public function __construct(
        DomainManagerInterface $domainManager,
        ContentManagerInterface $contentManager,
        PropertyAccessor $accessor = null
    ) {
        parent::__construct(
            $domainManager,
            $contentManager,
            ImagePathInterface::class,
            'imagePath',
            $accessor
        );
    }
}
