<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Twig\Mime;

use Klipper\Component\Mailer\Mime\SandboxInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * Templated email to enable the sandbox.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SandboxTemplatedEmail extends TemplatedEmail implements SandboxInterface
{
}
