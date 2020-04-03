<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineMigrationsBundle\Listener;

use Klipper\Bundle\DoctrineMigrationsBundle\Doctrine\Listener\SchemaNamespaceFixListener;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class CommandMigrationSubscriber implements EventSubscriberInterface
{
    /**
     * @var SchemaNamespaceFixListener[]
     */
    private $listeners;

    /**
     * Constructor.
     *
     * @param SchemaNamespaceFixListener[] $listeners The doctrine listeners
     */
    public function __construct(array $listeners)
    {
        $this->listeners = $listeners;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => [
                ['onCommand', 11],
            ],
        ];
    }

    /**
     * Enable the doctrine migrations listener for doctrine migration commands.
     *
     * @param ConsoleCommandEvent $event The event
     */
    public function onCommand(ConsoleCommandEvent $event): void
    {
        $cmd = $event->getCommand();

        if (null !== $cmd && 0 === strpos($cmd->getName(), 'doctrine:migrations:')) {
            foreach ($this->listeners as $listener) {
                $listener->enable();
            }
        }
    }
}
