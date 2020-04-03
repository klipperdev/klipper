<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineMigrationsBundle\Doctrine\Listener;

use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SchemaNamespaceFixListener
{
    /**
     * @var null|string
     */
    private $namespace;

    /**
     * @var bool
     */
    private $enabled = false;

    /**
     * Constructor.
     *
     * @param null|string $namespace The namespace
     */
    public function __construct(?string $namespace = null)
    {
        $this->namespace = $namespace;
    }

    /**
     * Enable the listener.
     */
    public function enable(): void
    {
        $this->enabled = true;
    }

    /**
     * @throws
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        if (!$this->enabled) {
            return;
        }

        $namespace = $this->namespace ?? $args->getEntityManager()->getConnection()
            ->getDatabasePlatform()->getDefaultSchemaName();

        $args->getSchema()->hasNamespace($namespace) || $args->getSchema()->createNamespace($namespace);
    }
}
