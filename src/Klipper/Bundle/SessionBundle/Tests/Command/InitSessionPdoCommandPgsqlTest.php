<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\SessionBundle\Tests\Command;

/**
 * Tests case for InitSessionPdoCommand with PgSQL driver.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class InitSessionPdoCommandPgsqlTest extends AbstractInitSessionPdoCommandTest
{
    /**
     * @var string
     */
    protected $driver = 'pgsql';
}
