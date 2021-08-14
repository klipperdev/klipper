<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal;

use Doctrine\Persistence\ManagerRegistry;
use Klipper\Component\DoctrineExtra\Util\RepositoryUtils;
use Klipper\Component\Portal\Entity\Repository\PortalUserRepositoryInterface;
use Klipper\Component\Portal\Model\PortalUserInterface;
use Klipper\Component\Security\Model\Traits\OrganizationalInterface;
use Klipper\Component\SecurityExtra\Helper\OrganizationalContextHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Portal Context helper.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalContextHelper
{
    protected TokenStorageInterface $tokenStorage;

    protected PortalContextInterface $context;

    protected AuthorizationCheckerInterface $authChecker;

    protected PortalUserRepositoryInterface $portalUserRepository;

    protected ?OrganizationalContextHelper $orgContextHelper;

    protected string $permissionName;

    protected ?string $routeParameterName = null;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        ManagerRegistry $doctrine,
        PortalContextInterface $context,
        AuthorizationCheckerInterface $authChecker,
        string $permissionName = 'back-office',
        ?OrganizationalContextHelper $orgContextHelper = null
    ) {
        $this->tokenStorage = $tokenStorage;
        /** @var PortalUserRepositoryInterface $portalUserRepo */
        $portalUserRepo = RepositoryUtils::getRepository($doctrine, PortalUserInterface::class, PortalUserRepositoryInterface::class);
        $this->portalUserRepository = $portalUserRepo;
        $this->context = $context;
        $this->authChecker = $authChecker;
        $this->permissionName = $permissionName;
        $this->orgContextHelper = $orgContextHelper;
    }

    public function setRouteParameterName(string $name): void
    {
        $this->routeParameterName = $name;
    }

    public function injectContext(Request $request): void
    {
        if (null === $this->routeParameterName) {
            return;
        }

        $attr = $request->attributes;
        $portalContext = $attr->has($this->routeParameterName);
        $portalName = $attr->get($this->routeParameterName.'_name');
        $portal = $attr->get($this->routeParameterName, $portalName);

        if (null === $portalName) {
            $routeParams = $attr->get('_route_params', []);
            $portalName = $routeParams[$portal] ?? false;
        }

        if (false !== $portalName && null !== $this->tokenStorage->getToken()) {
            $this->setCurrentPortalUser($portalName);
        }

        if (null !== $this->tokenStorage->getToken()
                && (($portalContext && null === $this->context->getCurrentPortalUser())
                    || (!$portalContext && !$this->authChecker->isGranted('perm:'.$this->permissionName)))) {
            throw new NotFoundHttpException();
        }

        if (null !== $this->orgContextHelper && null !== $portalUser = $this->context->getCurrentPortalUser()) {
            if ($portalUser instanceof OrganizationalInterface && null !== $org = $portalUser->getOrganization()) {
                $request->attributes->set('_organizational_name', $org->getName());
                $this->orgContextHelper->injectContext($request);
            }
        }
    }

    /**
     * Get the current portal user.
     *
     * @param string $portalName The current portal name
     */
    public function getCurrentPortalUser(?string $portalName): ?PortalUserInterface
    {
        $portalUser = null;

        if (null !== $portalName) {
            $portalUser = $this->portalUserRepository->findCurrentPortalUserByPortalName(
                $portalName,
                $this->getUser()
            );
        }

        return $portalUser;
    }

    /**
     * Set the current portal user defined by portal name,
     * in security portal context.
     *
     * @param string $portalName The current portal name
     */
    public function setCurrentPortalUser(?string $portalName): void
    {
        $this->context->setCurrentPortalUser($this->getCurrentPortalUser($portalName));
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @see TokenInterface::getUser()
     */
    private function getUser(): ?UserInterface
    {
        $token = $this->tokenStorage->getToken();
        $user = null;

        if (null !== $token) {
            $tUser = $token->getUser();
            $user = $tUser instanceof UserInterface ? $tUser : null;
        }

        return $user;
    }
}
