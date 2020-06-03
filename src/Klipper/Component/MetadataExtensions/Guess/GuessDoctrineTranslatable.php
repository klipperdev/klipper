<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Guess;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Gedmo\Translatable\TranslatableListener;
use Klipper\Component\DoctrineExtensionsExtra\Model\Traits\TranslatableInterface;
use Klipper\Component\Metadata\FieldMetadataBuilderInterface;
use Klipper\Component\Metadata\Guess\GuessFieldConfigInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessDoctrineTranslatable extends AbstractGuessDoctrine implements GuessFieldConfigInterface
{
    /**
     * @var null|false|TranslatableListener
     */
    private $cacheTranslatableListener;

    /**
     * @throws
     */
    public function guessFieldConfig(FieldMetadataBuilderInterface $builder): void
    {
        $class = $builder->getParent()->getClass();
        $manager = $this->getManager($class);
        $classMeta = null !== $manager ? $manager->getClassMetadata($class) : null;
        $fieldName = $builder->getField();

        if (null === $manager
                || null === $classMeta
                || null !== $builder->isTranslatable()
                || !$classMeta->hasField($fieldName)
                || (null === $translatableListener = $this->getTranslatableListener())
                || !$classMeta->getReflectionClass()->isSubclassOf(TranslatableInterface::class)) {
            return;
        }

        $config = $translatableListener->getConfiguration($manager, $class);

        if (isset($config['fields'])) {
            $builder->setTranslatable(\in_array($fieldName, $config['fields'], true));
        }
    }

    /**
     * Get the translatable listener.
     */
    private function getTranslatableListener(): ?TranslatableListener
    {
        if (null === $this->cacheTranslatableListener) {
            $this->cacheTranslatableListener = false;

            foreach ($this->registry->getManagers() as $om) {
                if ($om instanceof EntityManagerInterface && $om->getEventManager()->hasListeners(Events::postLoad)) {
                    foreach ($om->getEventManager()->getListeners(Events::postLoad) as $omListener) {
                        if ($omListener instanceof TranslatableListener) {
                            $this->cacheTranslatableListener = $omListener;

                            break 2;
                        }
                    }
                }
            }
        }

        return false !== $this->cacheTranslatableListener ? $this->cacheTranslatableListener : null;
    }
}
