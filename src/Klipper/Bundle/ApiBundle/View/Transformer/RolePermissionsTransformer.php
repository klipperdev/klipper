<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ApiBundle\View\Transformer;

use Klipper\Bundle\ApiBundle\Exception\InvalidArgumentException;
use Klipper\Component\MetadataExtensions\Permission\PermissionMetadataManagerInterface;
use Klipper\Component\Security\Model\RoleInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RolePermissionsTransformer implements GetViewTransformerInterface
{
    /**
     * @var PermissionMetadataManagerInterface
     */
    private $pmManager;

    /**
     * @var bool
     */
    private $reset;

    /**
     * Constructor.
     *
     * @param PermissionMetadataManagerInterface $pmManager The permission metadata manager
     * @param bool                               $reset     Check if the permission cache must be resetted
     */
    public function __construct(PermissionMetadataManagerInterface $pmManager, $reset = false)
    {
        $this->pmManager = $pmManager;
        $this->reset = $reset;
    }

    /**
     * {@inheritdoc}
     */
    public function getView($value)
    {
        if (!$value instanceof RoleInterface) {
            throw new InvalidArgumentException('The value must be an instance of '.RoleInterface::class);
        }

        if ($this->reset) {
            $this->pmManager->clear();
        }

        return $this->pmManager->getPermissions($value);
    }
}
