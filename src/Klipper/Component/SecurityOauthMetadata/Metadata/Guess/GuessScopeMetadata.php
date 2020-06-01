<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauthMetadata\Metadata\Guess;

use Klipper\Component\Metadata\ActionMetadataBuilderInterface;
use Klipper\Component\Metadata\Guess\GuessActionConfigInterface;
use Klipper\Component\SecurityOauthMetadata\Scope\ScopeMetadata;
use Klipper\Component\SecurityOauthMetadata\Scope\ScopeTypes;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessScopeMetadata implements GuessActionConfigInterface
{
    public function guessActionConfig(ActionMetadataBuilderInterface $builder): void
    {
        $objectBuilder = $builder->getParent();
        $configs = [];

        if (!$objectBuilder->isPublic()) {
            return;
        }

        if (\in_array($builder->getName(), ScopeTypes::READ_ACTIONS, true)) {
            $configs[] = [
                'scope' => [
                    ScopeMetadata::getManage($objectBuilder),
                    ScopeMetadata::getRead($objectBuilder),
                ],
                'allRequired' => false,
            ];
        } elseif (\in_array($builder->getName(), ScopeTypes::MANAGE_ACTIONS, true)) {
            $configs[] = [
                'scope' => [
                    ScopeMetadata::getManage($objectBuilder),
                ],
                'allRequired' => false,
            ];
        }

        if (!empty($configs)) {
            $builder->setDefaults(array_merge($builder->getDefaults(), [
                '_required_oauth_scopes' => $configs,
            ]));
        }
    }
}
