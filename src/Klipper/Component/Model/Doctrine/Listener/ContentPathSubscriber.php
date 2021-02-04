<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Model\Doctrine\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Klipper\Component\Content\ContentManagerInterface;

/**
 * Doctrine subscriber for the content path trait.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ContentPathSubscriber implements EventSubscriber
{
    private ContentManagerInterface $contentManager;

    private string $class;

    private string $getter;

    /**
     * @var string[]
     */
    private array $deleteFiles = [];

    public function __construct(ContentManagerInterface $contentManager, string $class, string $getter)
    {
        $this->contentManager = $contentManager;
        $this->class = $class;
        $this->getter = $getter;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
            Events::postFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $getter = $this->getter;

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if (is_a($entity, $this->class)) {
                if (null !== $path = $entity->{$getter}()) {
                    $uploaderName = $this->contentManager->getUploaderName($entity);

                    if (null !== $uploaderName) {
                        $this->deleteFiles[$uploaderName][] = $path;
                    }
                }
            }
        }
    }

    public function postFlush(): void
    {
        foreach ($this->deleteFiles as $uploaderName => $deletePaths) {
            $this->contentManager->remove($uploaderName, $deletePaths);
        }

        $this->deleteFiles = [];
    }
}
