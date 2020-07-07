<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\ImporterDatabase;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Klipper\Component\Importer\Exception\InvalidArgumentException;
use Klipper\Component\Importer\Pipeline\AbstractPipeline;
use Psr\Log\LoggerInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractDatabasePipeline extends AbstractPipeline
{
    protected array $connectionParams = [];

    protected ?Connection $connection = null;

    /**
     * @param array|Connection $connectionParams The DBAL connection instance or config
     */
    public function __construct($connectionParams, ?LoggerInterface $logger = null, int $batchSize = 1000)
    {
        parent::__construct($logger, $batchSize);

        if ($connectionParams instanceof Connection) {
            $this->connectionParams = [];
            $this->connection = $connectionParams;
        } elseif (\is_array($connectionParams)) {
            $this->connectionParams = $connectionParams;
        } else {
            throw new InvalidArgumentException(
                'The first argument must be an instance of Doctrine\DBAL\Connection or the array config to create the instance with the Driver Manager factory'
            );
        }
    }

    /**
     * @throws DBALException
     */
    public function extract(int $cursor, ?\DateTimeInterface $startAt = null): array
    {
        $connexion = $this->getConnexion();

        $res = $this->buildQueryBuilder($connexion, $startAt)
            ->setMaxResults($this->getBatchSize())
            ->setFirstResult($cursor * $this->getBatchSize())
            ->execute()
        ;

        return $res->fetchAll();
    }

    /**
     * @throws DBALException
     */
    protected function getConnexion(): Connection
    {
        if (null === $this->connection) {
            $this->connection = DriverManager::getConnection($this->connectionParams);
        }

        return $this->connection;
    }

    abstract protected function buildQueryBuilder(Connection $connection, ?\DateTimeInterface $startAt): QueryBuilder;
}
