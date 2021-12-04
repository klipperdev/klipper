<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Listener;

use Klipper\Component\Content\ContentManagerInterface;
use Klipper\Component\Content\Model\AttachmentInterface;
use Klipper\Component\Content\Uploader\Event\UploadFileCompletedEvent;
use Klipper\Component\DoctrineExtra\Util\ClassUtils;
use Klipper\Component\Resource\Domain\DomainManagerInterface;
use Klipper\Component\Resource\Exception\ConstraintViolationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\MimeTypesInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class AttachmentUploadSubscriber implements EventSubscriberInterface
{
    private DomainManagerInterface $domainManager;

    private ContentManagerInterface $contentManager;

    private MimeTypesInterface $mimeTypes;

    public function __construct(
        DomainManagerInterface $domainManager,
        ContentManagerInterface $contentManager,
        MimeTypesInterface $mimeTypes
    ) {
        $this->domainManager = $domainManager;
        $this->contentManager = $contentManager;
        $this->mimeTypes = $mimeTypes;
    }

    public static function getSubscribedEvents(): iterable
    {
        return [
            UploadFileCompletedEvent::class => [
                ['onUploadRequest', 0],
            ],
        ];
    }

    /**
     * @throws
     */
    public function onUploadRequest(UploadFileCompletedEvent $event): void
    {
        $config = $event->getConfig();
        $attachmentClass = $config->getAttachmentClass();
        $file = $event->getFile();
        $pathName = $file->getPathname();
        $payload = $event->getPayload();

        if (!$config->isAttachment() || !\is_object($payload)) {
            return;
        }

        $attachment = $this->domainManager->get($attachmentClass)->newInstance();
        $uploaderName = $this->contentManager->getUploaderName($attachment);

        if (null === $uploaderName || !is_a($attachment, AttachmentInterface::class)) {
            return;
        }

        $relativePath = $this->contentManager->buildRelativePath($uploaderName, $pathName);

        $attachment->setMainAttachment($payload);
        $attachment->setFilePath($relativePath);
        $attachment->setName(pathinfo($file instanceof UploadedFile ? $file->getClientOriginalName() : $file->getBasename(), PATHINFO_FILENAME));
        $attachment->setExtension($file->getExtension());
        $attachment->setSize($file->getSize());
        $attachment->setTypeMime($this->mimeTypes->guessMimeType($pathName));

        if (0 === strpos((string) $attachment->getTypeMime(), 'image/')) {
            $attachment->setImage(true);

            if (\is_array($imageInfo = getimagesize($pathName))) {
                $attachment->setWidth($imageInfo[0] ?? null);
                $attachment->setHeight($imageInfo[1] ?? null);
            }
        }

        if ($this->domainManager->has(ClassUtils::getClass($attachment))) {
            $res = $this->domainManager->get(ClassUtils::getClass($attachment))->upsert($attachment);

            if (!$res->isValid()) {
                $this->contentManager->remove($uploaderName, $relativePath);

                throw new ConstraintViolationException($res->getErrors());
            }
        }
    }
}
