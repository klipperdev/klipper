<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Guess;

use Klipper\Component\Metadata\MetadataRegistryInterface;
use Klipper\Component\Metadata\ObjectMetadataBuilderInterface;
use Klipper\Component\Metadata\Util\MetadataUtil;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessFinalDeepSearch implements
    GuessRegistryAwareInterface,
    GuessPostConfigInterface
{
    protected ?MetadataRegistryInterface $metadataRegistry = null;

    public function setRegistry(MetadataRegistryInterface $registry): void
    {
        $this->metadataRegistry = $registry;
    }

    public function guessPostConfig(ObjectMetadataBuilderInterface $builder): void
    {
        $deepSearchPaths = $builder->getDeepSearchPaths();

        if (!$this->metadataRegistry || empty($deepSearchPaths)) {
            return;
        }

        $names = $this->metadataRegistry->getNames()->all();
        $validDeepSearchPaths = [];

        foreach ($deepSearchPaths as $deepSearchPath) {
            $associationNames = explode('.', $deepSearchPath);
            $finalBuilder = null;

            foreach ($associationNames as $associationName) {
                $associationTarget = $this->getAssociationTarget($builder, $associationName);

                if (isset($names[$associationTarget])) {
                    $finalBuilder = $this->metadataRegistry->getBuilder($names[$associationTarget]);
                } else {
                    $finalBuilder = null;

                    break;
                }
            }

            if (null !== $finalBuilder) {
                foreach ($finalBuilder->getFields() ?? [] as $fieldMeta) {
                    if ($fieldMeta->isSearchable()) {
                        $validDeepSearchPaths[] = $deepSearchPath;

                        break;
                    }
                }
            }
        }

        $builder->setDeepSearchPaths($validDeepSearchPaths);
    }

    private function getAssociationTarget(ObjectMetadataBuilderInterface $builder, string $association): ?string
    {
        foreach ($builder->getAssociations() as $assoMeta) {
            if ($association === MetadataUtil::getName($assoMeta->getAssociation())) {
                return $assoMeta->getTarget();
            }
        }

        return null;
    }
}
