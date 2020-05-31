<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Listener;

use Klipper\Component\SecurityOauth\Annotation\OauthScope;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthScopeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments',
        ];
    }

    public function onKernelControllerArguments(KernelEvent $event): void
    {
        $request = $event->getRequest();
        $userScopes = (array) $request->attributes->get('oauth_scopes', []);
        /** @var OauthScope[] $requiredScopes */
        $requiredScopes = (array) $request->attributes->get('_required_oauth_scopes', []);

        if (empty($userScopes) || empty($requiredScopes)) {
            return;
        }

        foreach ($requiredScopes as $requiredScope) {
            if ($requiredScope->isAllRequired()) {
                foreach ($requiredScope->getScope() as $scope) {
                    if (!\in_array($scope, $userScopes, true)) {
                        throw new AccessDeniedException();
                    }
                }
            } else {
                $access = false;

                foreach ($requiredScope->getScope() as $scope) {
                    if (\in_array($scope, $userScopes, true)) {
                        $access = true;

                        break;
                    }
                }

                if (!$access) {
                    throw new AccessDeniedException();
                }
            }
        }
    }
}
