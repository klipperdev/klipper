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

use Gedmo\Translatable\Translatable;
use Klipper\Component\Mailer\Model\TemplateMessageInterface;
use Klipper\Component\Mailer\Model\Traits\TemplateMessageTrait;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TemplateMessage implements TemplateMessageInterface, Translatable
{
    use TemplateMessageTrait;

    protected ?int $id = null;

    public function getId()
    {
        return $this->id;
    }
}
