<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauthMetadata\Scope\Loader;

use Klipper\Component\Metadata\MetadataManagerInterface;
use Klipper\Component\SecurityOauth\Scope\Loader\AbstractScopeLoader;
use Klipper\Component\SecurityOauthMetadata\Scope\ScopeMetadata;
use Klipper\Component\SecurityOauthMetadata\Scope\ScopeTypes;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MetadataScopeLoader extends AbstractScopeLoader
{
    private MetadataManagerInterface $metadataManager;

    public function __construct(MetadataManagerInterface $metadataManager)
    {
        $this->metadataManager = $metadataManager;
    }

    public function load(): array
    {
        $validScopes = [];

        foreach ($this->metadataManager->all() as $metadata) {
            $read = false;
            $manage = false;

            if ($metadata->isPublic()) {
                foreach ($metadata->getResources() as $resource) {
                    $this->addResource($resource);
                }

                foreach ($metadata->getActions() as $action) {
                    if (\in_array($action->getName(), ScopeTypes::READ_ACTIONS, true)) {
                        $read = true;
                        $validScopes[] = ScopeMetadata::getRead($metadata);
                    } elseif (\in_array($action->getName(), ScopeTypes::MANAGE_ACTIONS, true)) {
                        $manage = true;
                        $validScopes[] = ScopeMetadata::getManage($metadata);
                    }

                    if ($read && $manage) {
                        break;
                    }
                }
            }
        }

        sort($validScopes);

        return $validScopes;
    }
}
