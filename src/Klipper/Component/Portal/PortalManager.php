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

use Doctrine\ORM\EntityManagerInterface;
use Klipper\Component\Portal\Entity\Repository\PortalUserRepositoryInterface;
use Klipper\Component\Portal\Model\PortalUserInterface;
use Klipper\Component\Security\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalManager implements PortalManagerInterface
{
    private TokenStorageInterface $tokenStorage;

    private EntityManagerInterface $em;

    /**
     * @var null|AvailablePortal[]
     */
    private ?array $availablePortals = null;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $em
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    public function getAvailablePortals(): array
    {
        if (null === $this->availablePortals) {
            $this->availablePortals = [];

            $token = $this->tokenStorage->getToken();
            $user = null !== $token ? $token->getUser() : null;

            if ($user instanceof UserInterface) {
                $repo = $this->em->getRepository(PortalUserInterface::class);

                if ($repo instanceof PortalUserRepositoryInterface) {
                    $this->availablePortals = $repo->getAvailablePortals($user);
                }
            }
        }

        return $this->availablePortals;
    }
}
