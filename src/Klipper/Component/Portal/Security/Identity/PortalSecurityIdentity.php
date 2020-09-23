<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Security\Identity;

use Klipper\Component\DoctrineExtra\Util\ClassUtils;
use Klipper\Component\Portal\Model\PortalInterface;
use Klipper\Component\Portal\Model\PortalUserInterface;
use Klipper\Component\Portal\PortalContextInterface;
use Klipper\Component\Security\Identity\AbstractSecurityIdentity;
use Klipper\Component\Security\Identity\GroupSecurityIdentity;
use Klipper\Component\Security\Identity\RoleSecurityIdentity;
use Klipper\Component\Security\Identity\SecurityIdentityInterface;
use Klipper\Component\Security\Model\GroupInterface;
use Klipper\Component\Security\Model\Traits\GroupableInterface;
use Klipper\Component\Security\Model\Traits\RoleableInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalSecurityIdentity extends AbstractSecurityIdentity
{
    /**
     * Creates a oauth scope security identity from a ScopeEntityInterface.
     *
     * @param string $scope The oauth scope
     *
     * @return static
     */
    public static function fromAccount(PortalInterface $portal): self
    {
        return new self('portal', $portal->getName());
    }

    /**
     * Creates a portal security identity from a TokenInterface.
     *
     * @param TokenInterface              $token         The token
     * @param null|PortalContextInterface $context       The portal context
     * @param null|RoleHierarchyInterface $roleHierarchy The role hierarchy
     *
     * @return SecurityIdentityInterface[]
     */
    public static function fromToken(
        TokenInterface $token,
        ?PortalContextInterface $context = null,
        ?RoleHierarchyInterface $roleHierarchy = null
    ): array {
        $user = $token->getUser();

        if (!$user instanceof UserInterface || null === $context) {
            return [];
        }

        $sids = [];
        $portal = $context->getCurrentPortal();
        $userPortal = $context->getCurrentPortalUser();
        $portalRoles = [];

        if ($portal) {
            $sids[] = self::fromAccount($portal);
        }

        if (null !== $userPortal) {
            $sids = array_merge($sids, self::getPortalGroups($userPortal));
            $portalRoles = self::getPortalUserRoles($userPortal, $roleHierarchy);
        }

        foreach ($portalRoles as $role) {
            $sids[] = RoleSecurityIdentity::fromAccount($role);
        }

        return $sids;
    }

    /**
     * Get the security identities for portal groups of user.
     *
     * @param PortalUserInterface $user The portal user
     *
     * @return GroupSecurityIdentity[]
     */
    private static function getPortalGroups(PortalUserInterface $user): array
    {
        $sids = [];
        $portalName = $user->getPortal() ? $user->getPortal()->getName() : null;

        if (null !== $portalName && $user instanceof GroupableInterface) {
            foreach ($user->getGroups() as $group) {
                if ($group instanceof GroupInterface) {
                    $sids[] = new GroupSecurityIdentity(ClassUtils::getClass($group), $group->getName().'__'.$portalName);
                }
            }
        }

        return $sids;
    }

    /**
     * Get the portal roles of user.
     *
     * @param PortalUserInterface         $user          The portal user
     * @param null|RoleHierarchyInterface $roleHierarchy The role hierarchy
     *
     * @return string[]
     */
    private static function getPortalUserRoles(PortalUserInterface $user, ?RoleHierarchyInterface $roleHierarchy = null): array
    {
        $roles = [];

        if ($user instanceof RoleableInterface && $user instanceof PortalUserInterface) {
            $portal = $user->getPortal();

            if ($portal) {
                $roles = self::buildPortalUserRoles($roles, $user, $portal->getName());
                $roles = self::buildPortalRoles($roles, $portal);
            }

            if ($roleHierarchy instanceof RoleHierarchyInterface) {
                $roles = $roleHierarchy->getReachableRoleNames($roles);
            }
        }

        return $roles;
    }

    /**
     * Build the portal user roles.
     *
     * @param string[]          $roles      The roles
     * @param RoleableInterface $user       The portal user
     * @param string            $portalName The portal name
     *
     * @return string[]
     */
    private static function buildPortalUserRoles(array $roles, RoleableInterface $user, string $portalName): array
    {
        foreach ($user->getRoles() as $role) {
            $roles[] = $role.'__'.$portalName;
        }

        return $roles;
    }

    /**
     * Build the user portal roles.
     *
     * @param string[]        $roles  The roles
     * @param PortalInterface $portal The portal of user
     *
     * @return string[]
     */
    private static function buildPortalRoles(array $roles, PortalInterface $portal): array
    {
        $portalName = $portal->getName();

        if ($portal instanceof RoleableInterface) {
            $existingRoles = [];

            foreach ($roles as $role) {
                $existingRoles[] = $role;
            }

            foreach ($portal->getRoles() as $portalRole) {
                $roleName = $portalRole;

                if (!\in_array($roleName, $existingRoles, true)) {
                    $roles[] = $roleName.'__'.$portalName;
                    $existingRoles[] = $roleName;
                }
            }
        }

        return $roles;
    }
}
