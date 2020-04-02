<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\ExpressionLanguage\Functions;

use Klipper\Component\Security\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Current user id function.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class CurrentUserIdFunction extends BaseFunction
{
    /**
     * Constructor.
     *
     * @param TokenStorage $tokenStorage The token storage
     * @param string       $function     The name of function
     */
    public function __construct(TokenStorage $tokenStorage, $function = 'current_user_id')
    {
        parent::__construct($function, static function () use ($tokenStorage) {
            $token = $tokenStorage->getToken();
            $userId = null;

            if ($token && $token->getUser() && ($user = $token->getUser()) instanceof UserInterface) {
                $userId = $user->getId();
            }

            return $userId;
        });
    }
}
