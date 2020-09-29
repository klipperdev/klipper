<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice\DataLoader;

use Klipper\Component\DataLoader\DataLoaderInterface;
use Klipper\Component\DataLoader\Util\DataLoaderTranslationUtil;
use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;
use Klipper\Component\DoctrineExtensions\Util\SqlFilterUtil;
use Klipper\Component\Resource\Domain\DomainInterface;
use Klipper\Component\Resource\ResourceList;
use Klipper\Component\Resource\ResourceListInterface;
use Klipper\Contracts\Model\TranslatableInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class BaseChoiceLoader implements DataLoaderInterface
{
    protected DomainInterface $domain;

    protected ChoiceConfiguration $config;

    protected ChoiceProcessor $processor;

    protected string $defaultLocale;

    protected PropertyAccessor $accessor;

    protected bool $hasNewEntities = false;

    protected bool $hasUpdatedEntities = false;

    /**
     * @param DomainInterface          $domain    The resource domain of choice
     * @param null|ChoiceConfiguration $config    The choice configuration
     * @param null|ChoiceProcessor     $processor The choice processor
     */
    public function __construct(
        DomainInterface $domain,
        ?ChoiceConfiguration $config = null,
        ?ChoiceProcessor $processor = null,
        string $defaultLocale = 'en',
        PropertyAccessor $accessor = null
    ) {
        $this->domain = $domain;
        $this->config = $config ?? new ChoiceConfiguration();
        $this->processor = $processor ?? new ChoiceProcessor();
        $this->defaultLocale = $defaultLocale;
        $this->accessor = $accessor ?? PropertyAccess::createPropertyAccessor();
    }

    public function load($resource): ResourceListInterface
    {
        $content = $this->loadContent($resource);
        $config = $this->processor->process($this->config, [$content]);

        return $this->doLoad($config);
    }

    /**
     * Check if the new choices are loaded.
     */
    public function hasNewEntities(): bool
    {
        return $this->hasNewEntities;
    }

    /**
     * Check if the choices are updated.
     */
    public function hasUpdatedEntities(): bool
    {
        return $this->hasUpdatedEntities;
    }

    /**
     * Load the resource content.
     *
     * @param mixed $resource The resource
     */
    abstract protected function loadContent($resource): array;

    /**
     * Find and attach entity in the map entities.
     *
     * @param array|ChoiceInterface[] $upsertEntities The map of upserted entities (by reference)
     * @param array|ChoiceInterface[] $entities       The map of entities in database
     * @param array                   $item           The item
     */
    protected function convertToEntity(array &$upsertEntities, array $entities, array $item): void
    {
        $itemUniqueValue = $item['value'];

        if (!isset($entities[$itemUniqueValue])) {
            /** @var ChoiceInterface $entity */
            $entity = $this->domain->newInstance();
            $entity->setValue($itemUniqueValue);

            if ($entity instanceof TranslatableInterface) {
                $entity->setAvailableLocales([$this->defaultLocale]);
            }

            $upsertEntities[$itemUniqueValue] = $entity;
            $this->hasNewEntities = true;
        } else {
            $entity = $entities[$itemUniqueValue];
        }

        $this->mapProperties($upsertEntities, $entity, $item);
    }

    protected function mapProperties(array &$upsertEntities, ChoiceInterface $entity, array $item): void
    {
        $edited = false;

        foreach ($item as $field => $value) {
            if ('translations' !== $field) {
                $fieldValue = $this->accessor->getValue($entity, $field);

                if ($fieldValue !== $value && null !== $value) {
                    $this->accessor->setValue($entity, $field, $value);
                    $edited = true;
                }
            }
        }

        if ($entity instanceof TranslatableInterface) {
            $meta = $this->domain->getObjectManager()->getClassMetadata($this->domain->getClass());
            $transClass = $meta->getAssociationTargetClass('translations');
            $translations = DataLoaderTranslationUtil::getTranslationsMap($entity);

            $edited = DataLoaderTranslationUtil::injectTranslations(
                $entity,
                $transClass,
                $item['translations'],
                $translations
            ) || $edited;
        }

        if ($edited) {
            $this->hasUpdatedEntities = true;
            $uniqueValue = $entity->getValue();

            if (!isset($upsertEntities[$uniqueValue])) {
                $upsertEntities[$uniqueValue] = $entity;
            }
        }
    }

    /**
     * Action to load the config of permissions in doctrine.
     *
     * @param array $config The config of permissions
     */
    private function doLoad(array $config): ResourceListInterface
    {
        $om = $this->domain->getObjectManager();
        $filters = SqlFilterUtil::disableFilters($om, [], true);
        $res = new ResourceList();

        foreach ($config as $type => $items) {
            $entities = $this->getChoices($type);
            $upsertEntities = [];

            foreach ($items as $item) {
                $this->convertToEntity($upsertEntities, $entities, $item);
            }

            // upsert entities
            if (\count($upsertEntities) > 0) {
                $res->addAll($this->domain->upserts($upsertEntities, true));
            }
        }

        SqlFilterUtil::enableFilters($om, $filters);

        return $res;
    }

    /**
     * Get the map of choices.
     */
    private function getChoices(string $type): array
    {
        /** @var ChoiceInterface[] $choices */
        $choices = $this->domain->getRepository()->findBy([
            'type' => $type,
        ], [
            'position' => 'asc',
        ]);
        $cache = [];

        foreach ($choices as $choice) {
            $cache[$choice->getValue()] = $choice;
        }

        return $cache;
    }
}
