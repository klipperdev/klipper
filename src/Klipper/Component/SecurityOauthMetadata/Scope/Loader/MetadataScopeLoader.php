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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MetadataScopeLoader extends AbstractScopeLoader
{
    public const READ_ACTIONS = [
        'list',
        'view',
    ];

    public const MANAGE_ACTIONS = [
        'create',
        'upsert',
        'update',
        'delete',
        'undelete',
        'creates',
        'upserts',
        'updates',
        'deletes',
        'undeletes',
    ];

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
                    if (\in_array($action->getName(), static::READ_ACTIONS, true)) {
                        $read = true;
                        $validScopes[] = sprintf('meta/%s.readonly', $metadata->getName());
                    } elseif (\in_array($action->getName(), static::MANAGE_ACTIONS, true)) {
                        $manage = true;
                        $validScopes[] = sprintf('meta/%s', $metadata->getName());
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
