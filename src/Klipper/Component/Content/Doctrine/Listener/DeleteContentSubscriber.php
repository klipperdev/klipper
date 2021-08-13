<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Doctrine\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Klipper\Component\Content\Doctrine\DeleteContentConfig;
use Klipper\Component\Content\Message\DoctrineDeleteContentMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DeleteContentSubscriber implements EventSubscriber
{
    private MessageBusInterface $messageBus;

    private PropertyAccessorInterface $propertyAccessor;

    /**
     * @var DeleteContentConfig[]
     */
    private array $configs = [];

    /**
     * @var DoctrineDeleteContentMessage[]
     */
    private array $deleteContentMessages = [];

    public function __construct(
        MessageBusInterface $messageBus,
        ?PropertyAccessorInterface $propertyAccessor = null
    ) {
        $this->messageBus = $messageBus;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
            Events::postFlush,
        ];
    }

    public function addConfig(DeleteContentConfig $config): void
    {
        $this->configs[] = $config;
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $deleteTypes = [];

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            foreach ($this->configs as $config) {
                if (is_a($entity, $config->getClassname())) {
                    $path = $this->propertyAccessor->getValue($entity, $config->getPropertyPath());

                    if (null !== $path) {
                        $deleteTypes[$config->getUploaderName()][] = $path;
                    }
                }
            }
        }

        foreach ($deleteTypes as $uploaderName => $paths) {
            $this->deleteContentMessages[] = new DoctrineDeleteContentMessage(
                $uploaderName,
                $paths,
            );
        }
    }

    public function postFlush(): void
    {
        foreach ($this->deleteContentMessages as $message) {
            $this->messageBus->dispatch($message);
        }

        $this->deleteContentMessages = [];
    }
}
