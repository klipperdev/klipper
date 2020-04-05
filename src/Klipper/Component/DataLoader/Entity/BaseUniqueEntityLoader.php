<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DataLoader\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Klipper\Component\DataLoader\DataLoaderInterface;
use Klipper\Component\DataLoader\Exception\ConsoleResourceException;
use Klipper\Component\DataLoader\Exception\InvalidArgumentException;
use Klipper\Component\DataLoader\Exception\RuntimeException;
use Klipper\Component\DoctrineExtensionsExtra\Model\BaseTranslation;
use Klipper\Component\DoctrineExtensionsExtra\Model\Traits\TranslatableInterface;
use Klipper\Component\Model\Traits\NameableInterface;
use Klipper\Component\Resource\Domain\DomainInterface;
use Klipper\Component\Security\Model\Traits\OrganizationalInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class BaseUniqueEntityLoader implements DataLoaderInterface
{
    /**
     * @var DomainInterface
     */
    protected $domain;

    /**
     * @var ClassMetadata|ClassMetadataInfo
     */
    protected $metadata;

    /**
     * @var ConfigurationInterface
     */
    protected $config;

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    /**
     * @var bool
     */
    protected $hasNewEntities = false;

    /**
     * @var bool
     */
    protected $hasUpdatedEntities = false;

    /**
     * Constructor.
     *
     * @param DomainInterface                $domain        The resource domain of entity
     * @param null|UniqueEntityConfiguration $config        The amenity configuration
     * @param Processor                      $processor     The processor
     * @param string                         $defaultLocale The default locale
     * @param null|PropertyAccessor          $accessor      The property accessor
     */
    public function __construct(
        DomainInterface $domain,
        UniqueEntityConfiguration $config = null,
        Processor $processor = null,
        string $defaultLocale = 'en',
        PropertyAccessor $accessor = null
    ) {
        $this->domain = $domain;
        $this->metadata = $domain->getObjectManager()->getClassMetadata($domain->getClass());
        $this->config = $config ?: new UniqueEntityConfiguration($domain);
        $this->processor = $processor ?: new Processor();
        $this->defaultLocale = $defaultLocale;
        $this->accessor = $accessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource): void
    {
        if (!$this->supports($resource)) {
            throw new InvalidArgumentException('The resource is not supported by this data loader');
        }

        $content = $this->loadContent($resource);
        $items = $this->processor->processConfiguration($this->config, [$content]);

        $this->doLoad($items);
    }

    /**
     * Check if the new entities are loaded.
     */
    public function hasNewEntities(): bool
    {
        return $this->hasNewEntities;
    }

    /**
     * Check if the entities are updated.
     */
    public function hasUpdatedEntities(): bool
    {
        return $this->hasUpdatedEntities;
    }

    /**
     * Create a new instance of entity.
     *
     * @param array $options The options
     */
    protected function newInstance(array $options): object
    {
        return $this->domain->newInstance($options);
    }

    /**
     * Load the resource content.
     *
     * @param mixed $resource The resource
     */
    abstract protected function loadContent($resource): array;

    /**
     * Action to load the config of entities in doctrine.
     *
     * @param array $items The items
     */
    private function doLoad(array $items): void
    {
        if (!\in_array(OrganizationalInterface::class, class_implements($this->domain->getClass()), true)) {
            throw new InvalidArgumentException(sprintf('The "%s" class must implemented "%s"', $this->domain->getClass(), OrganizationalInterface::class));
        }

        if (!\in_array(NameableInterface::class, class_implements($this->domain->getClass()), true)) {
            throw new InvalidArgumentException(sprintf('The "%s" class must implemented "%s"', $this->domain->getClass(), NameableInterface::class));
        }

        /** @var NameableInterface[] $list */
        $list = $this->domain->getRepository()->findBy(['organization' => null]);
        /** @var NameableInterface[] $entities */
        $entities = [];
        /** @var NameableInterface[] $upsertEntities */
        $upsertEntities = [];

        foreach ($list as $entity) {
            $entities[$entity->getName()] = $entity;
        }

        foreach ($items as $item) {
            $this->convertToEntity($upsertEntities, $entities, $item);
        }

        // upsert entities
        if (\count($upsertEntities) > 0) {
            $res = $this->domain->upserts($upsertEntities, true);

            if ($res->hasErrors()) {
                throw new ConsoleResourceException($res, 'name');
            }
        }
    }

    /**
     * Find and attach entity in the map entities.
     *
     * @param array|NameableInterface[] $upsertEntities The map of upserted entities (by reference)
     * @param array|NameableInterface[] $entities       The map of entities in database
     * @param array                     $item           The item
     */
    private function convertToEntity(array &$upsertEntities, array $entities, array $item): void
    {
        $itemName = $item['name'];
        $newEntity = false;

        if (!isset($entities[$itemName])) {
            $entity = $this->newInstance($item);
            $entity->setName($itemName);

            if ($entity instanceof TranslatableInterface) {
                $entity->setAvailableLocales([$this->defaultLocale]);
            }

            $upsertEntities[$itemName] = $entity;
            $this->hasNewEntities = true;
        } else {
            $entity = $entities[$itemName];
        }

        $this->mapProperties($upsertEntities, $entities, $entity, $item, $newEntity);
    }

    /**
     * @param bool $newEntity
     *
     * @throws
     */
    private function mapProperties(array &$upsertEntities, array $entities, NameableInterface $entity, array $item, $newEntity = false): void
    {
        /** @var BaseTranslation[] $translations */
        $translations = [];
        $edited = false;

        foreach ($this->metadata->getFieldNames() as $fieldName) {
            if (\array_key_exists($fieldName, $item)) {
                $type = $this->metadata->getTypeOfField($fieldName);
                $value = $this->accessor->getValue($entity, $fieldName);
                $itemValue = $item[$fieldName];

                switch ($type) {
                    case Type::DATETIME:
                    case Type::DATETIMETZ:
                    case Type::DATE:
                    case Type::TIME:
                        $itemValue = null !== $itemValue ? new \DateTime($itemValue) : $itemValue;

                        if ($itemValue instanceof \DateTime) {
                            $this->accessor->setValue($entity, $fieldName, $itemValue);
                        }

                        break;
                    default:
                        if (!empty($itemValue) && $value !== $itemValue) {
                            $this->accessor->setValue($entity, $fieldName, $itemValue);
                            $edited = true;

                            if (!$newEntity) {
                                $this->hasUpdatedEntities = true;
                            }
                        }

                        break;
                }
            }
        }

        if ($this->isTranslatable()) {
            /** @var TranslatableInterface $entity */
            foreach ($entity->getTranslations() as $translation) {
                /* @var BaseTranslation $translation */
                $translations[$translation->getLocale().':'.$translation->getField()] = $translation;
            }
        }

        foreach ($this->metadata->getAssociationNames() as $associationName) {
            if (\array_key_exists($associationName, $item) && !empty($item[$associationName])) {
                $mapping = $this->metadata->getAssociationMapping($associationName);

                switch ($mapping['type']) {
                    case ClassMetadataInfo::ONE_TO_ONE:
                        throw new InvalidArgumentException(sprintf('The one-to-one association "%s" is not supported', $associationName));

                        break;
                    case ClassMetadataInfo::MANY_TO_ONE:
                        throw new InvalidArgumentException(sprintf('The many-to-one association "%s" is not supported', $associationName));

                        break;
                    case ClassMetadataInfo::ONE_TO_MANY:
                        if ('translations' === $associationName && $this->isTranslatable()) {
                            $transClass = $mapping['targetEntity'];

                            foreach ($item[$associationName] as $transLocale => $transItem) {
                                foreach ($transItem as $transField => $transValue) {
                                    $id = $transLocale.':'.$transField;

                                    if (!isset($translations[$id])) {
                                        $edited = true;
                                        /** @var BaseTranslation $transEntity */
                                        $transEntity = new $transClass($transLocale, $transField, $transValue);
                                        $transEntity->setObject($entity);
                                        $entity->getTranslations()->add($transEntity);
                                        $availables = $entity->getAvailableLocales();
                                        $availables[] = $transLocale;
                                        $availables = array_unique($availables);
                                        sort($availables);
                                        $entity->setAvailableLocales($availables);
                                    } elseif ($translations[$id]->getContent() !== $transValue) {
                                        $edited = true;
                                        $translations[$id]->setContent($transValue);
                                    }
                                }
                            }
                        } else {
                            throw new InvalidArgumentException(sprintf('The one-to-many association "%s" is not supported', $associationName));
                        }

                        break;
                    case ClassMetadataInfo::MANY_TO_MANY:
                        foreach ($item[$associationName] as $child) {
                            if (!isset($child['criteria']['name'])) {
                                throw new InvalidArgumentException(sprintf('The "name" criteria is required for the "%s" association', $associationName));
                            }
                            if (!isset($entities[$child['criteria']['name']]) && !isset($upsertEntities[$child['criteria']['name']])) {
                                throw new RuntimeException(sprintf('The entity "%s" does not exist', $child['criteria']['name']));
                            }

                            /** @var Collection $coll */
                            $coll = $this->accessor->getValue($entity, $associationName);
                            $childEntity = $entities[$child['criteria']['name']]
                                ?? $upsertEntities[$child['criteria']['name']];

                            if (!$coll->contains($childEntity)) {
                                $coll->add($childEntity);
                                $edited = true;

                                if (!$newEntity) {
                                    $this->hasUpdatedEntities = true;
                                }
                            }
                        }

                        break;
                    default:
                        break;
                }
            }
        }

        if ($edited) {
            $this->hasUpdatedEntities = true;

            if (!isset($upsertEntities[$entity->getName()])) {
                $upsertEntities[$entity->getName()] = $entity;
            }
        }
    }

    /**
     * Check if the entity is translatable.
     */
    private function isTranslatable(): bool
    {
        return \in_array(TranslatableInterface::class, class_implements($this->metadata->getName()), true);
    }
}