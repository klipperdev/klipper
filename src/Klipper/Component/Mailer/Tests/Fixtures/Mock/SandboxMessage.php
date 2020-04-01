<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Tests\Fixtures\Mock;

use Klipper\Component\Mailer\Mime\SandboxInterface;
use Symfony\Component\Mime\Message;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SandboxMessage extends Message implements SandboxInterface
{
}
