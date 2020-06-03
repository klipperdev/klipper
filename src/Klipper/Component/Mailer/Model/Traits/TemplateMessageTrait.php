<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use Klipper\Component\Mailer\Model\TemplateMessageInterface;

/**
 * Template message trait.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait TemplateMessageTrait
{
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected ?string $name = null;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected ?string $type = null;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $enabled = true;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected ?string $label = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected ?string $description = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $body = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?\DateTime $createdAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?\DateTime $updatedAt = null;

    /**
     * @see TemplateMessageInterface::setName()
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @see TemplateMessageInterface::getName()
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @see TemplateMessageInterface::setType()
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @see TemplateMessageInterface::getType()
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @see TemplateMessageInterface::setLabel()
     */
    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @see TemplateMessageInterface::getLabel()
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @see TemplateMessageInterface::setDescription()
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @see TemplateMessageInterface::getDescription()
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @see TemplateMessageInterface::setBody()
     */
    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @see TemplateMessageInterface::getBody()
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @see TemplateMessageInterface::setCreatedAt()
     */
    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @see TemplateMessageInterface::getCreatedAt()
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @see TemplateMessageInterface::setUpdatedAt()
     */
    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @see TemplateMessageInterface::getUpdatedAt()
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}
