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
 * Tests case for InitSessionPdoCommand with MySQL driver.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class InitSessionPdoCommandMysqlTest extends AbstractInitSessionPdoCommandTest
{
    /**
     * @var string
     */
    protected $driver = 'mysql';
}
