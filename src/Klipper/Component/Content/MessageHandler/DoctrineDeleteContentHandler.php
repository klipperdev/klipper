<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\MessageHandler;

use Klipper\Component\Content\Batch\DeleteContent;
use Klipper\Component\Content\Message\DoctrineDeleteContentMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DoctrineDeleteContentHandler implements MessageHandlerInterface
{
    private DeleteContent $deleteContent;

    public function __construct(DeleteContent $deleteContent)
    {
        $this->deleteContent = $deleteContent;
    }

    public function __invoke(DoctrineDeleteContentMessage $message): void
    {
        $this->deleteContent->removes($message->getUploaderName(), $message->getPaths());
    }
}
