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
use Doctrine\ORM\EntityRepository;
use Klipper\Component\DoctrineExtensions\Util\SqlFilterUtil;
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
                /** @var PortalUserInterface $class */
                $class = $repo->getClassName();

                if ($repo instanceof EntityRepository) {
                    $filters = SqlFilterUtil::disableFilters($this->em, [], true);
                    /** @var PortalUserInterface[] $res */
                    $res = $repo->createQueryBuilder('pu')
                        ->addSelect('p')
                        ->leftJoin('pu.'.$class::getPortalAssociationName(), 'p')
                        ->where('pu.user = :user')
                        ->andWhere('pu.enabled = true')
                        ->andWhere('p.portalEnabled = true')
                        ->orderBy('p.name', 'asc')
                        ->setParameter('user', $user)
                        ->getQuery()
                        ->getResult()
                    ;

                    foreach ($res as $item) {
                        $portal = $item->getPortal();

                        if (null !== $portal) {
                            $this->availablePortals[] = new AvailablePortal(
                                $portal->getId(),
                                $portal->getPortalName(),
                                method_exists($portal, 'getName') ? $portal->getName() : $portal->getPortalName()
                            );
                        }
                    }

                    SqlFilterUtil::enableFilters($this->em, $filters);
                }
            }
        }

        return $this->availablePortals;
    }
}
