<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bridge\SmsSender\Amazon\Transport;

use Klipper\Component\SmsSender\Exception\UnsupportedSchemeException;
use Klipper\Component\SmsSender\Transport\AbstractTransportFactory;
use Klipper\Component\SmsSender\Transport\Dsn;
use Klipper\Component\SmsSender\Transport\TransportInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SnsTransportFactory extends AbstractTransportFactory
{
    public function create(Dsn $dsn): TransportInterface
    {
        $scheme = $dsn->getScheme();

        if ('api' === $scheme) {
            return new SnsTransport(
                $this->getUser($dsn),
                $this->getPassword($dsn),
                $dsn->getOption('region'),
                $dsn->getOption('region_id'),
                $dsn->getOption('type'),
                $this->dispatcher,
                $this->client,
                $this->logger
            );
        }

        throw new UnsupportedSchemeException($dsn, ['api']);
    }

    public function supports(Dsn $dsn): bool
    {
        return 'sns' === $dsn->getHost();
    }
}
