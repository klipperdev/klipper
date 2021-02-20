<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Twig\Extension;

use Klipper\Component\Metadata\MetadataManagerInterface;
use Klipper\Component\Metadata\ObjectMetadataInterface;
use Klipper\Component\Metadata\Util\MetadataUtil;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MetadataExtension extends AbstractExtension
{
    private MetadataManagerInterface $metadataManager;

    private TranslatorInterface $translator;

    public function __construct(
        MetadataManagerInterface $metadataManager,
        TranslatorInterface $translator
    ) {
        $this->metadataManager = $metadataManager;
        $this->translator = $translator;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('metadata', [$this, 'getMetadata']),
            new TwigFunction('metadataField', [$this, 'getMetadataField']),
            new TwigFunction('metadataAssociation', [$this, 'getMetadataAssociation']),
            new TwigFunction('metadataLabel', [$this, 'getMetadataLabel']),
            new TwigFunction('metadataPluralLabel', [$this, 'getMetadataPluralLabel']),
            new TwigFunction('metadataFieldLabel', [$this, 'getMetadataFieldLabel']),
            new TwigFunction('metadataAssociationLabel', [$this, 'getMetadataAssociationLabel']),
            new TwigFunction('ml', [$this, 'getMetadataLabel']),
            new TwigFunction('mpl', [$this, 'getMetadataPluralLabel']),
            new TwigFunction('mfl', [$this, 'getMetadataFieldLabel']),
            new TwigFunction('mal', [$this, 'getMetadataAssociationLabel']),
        ];
    }

    public function getMetadata(string $name): ObjectMetadataInterface
    {
        return $this->metadataManager->getByName($name);
    }

    public function getMetadataField(string $name, string $field): string
    {
        $metadata = $this->metadataManager->getByName($name);

        return $metadata->getFieldByName($field);
    }

    public function getMetadataAssociation(string $name, string $association): string
    {
        $metadata = $this->metadataManager->getByName($name);

        return $metadata->getAssociationByName($association);
    }

    public function getMetadataLabel(string $name): string
    {
        $metadata = $this->metadataManager->getByName($name);

        return MetadataUtil::getTrans($this->translator, $metadata->getLabel(), $metadata->getTranslationDomain(), $metadata->getName());
    }

    public function getMetadataPluralLabel(string $name): string
    {
        $metadata = $this->metadataManager->getByName($name);

        return MetadataUtil::getTrans($this->translator, $metadata->getPluralLabel(), $metadata->getTranslationDomain(), $metadata->getName());
    }

    public function getMetadataFieldLabel(string $name, string $field): string
    {
        $metadata = $this->metadataManager->getByName($name);
        $fieldMeta = $metadata->getFieldByName($field);

        return MetadataUtil::getTrans($this->translator, $fieldMeta->getLabel(), $fieldMeta->getTranslationDomain(), $fieldMeta->getName());
    }

    public function getMetadataAssociationLabel(string $name, string $association): string
    {
        $metadata = $this->metadataManager->getByName($name);
        $assoMeta = $metadata->getAssociationByName($association);

        return MetadataUtil::getTrans($this->translator, $assoMeta->getLabel(), $assoMeta->getTranslationDomain(), $assoMeta->getName());
    }
}
