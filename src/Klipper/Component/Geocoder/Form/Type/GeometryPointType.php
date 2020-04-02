<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Geocoder\Form\Type;

use Klipper\Component\Geocoder\Form\DataTransformer\ArrayToPointTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Geometry Point Form Type.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GeometryPointType extends AbstractType
{
    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @var ArrayToPointTransformer
     */
    private $transformer;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->transformer = new ArrayToPointTransformer();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $fOptions = [];

        if (NumberType::class === $options['field_type']) {
            $fOptions['scale'] = $options['scale'];
        }

        $builder
            ->add('x', $options['field_type'], array_merge($fOptions, $options['x_options']))
            ->add('y', $options['field_type'], array_merge($fOptions, $options['y_options']))

            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                if (null !== $event->getForm()->getParent()) {
                    $parentData = $event->getForm()->getParent()->getData();

                    if (\is_object($parentData) || \is_array($parentData)) {
                        $val = $this->accessor->getValue($parentData, $this->getPropertyPath($event));
                        $data = $this->transformer->transform($val);
                        $event->setData($data);
                    }
                }
            })

            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
                $data = $this->transformer->reverseTransform($event->getData());
                $event->setData($data);

                if (null !== $event->getForm()->getParent()) {
                    $parentData = $event->getForm()->getParent()->getData();
                    $this->accessor->setValue($parentData, $this->getPropertyPath($event), $data);
                }
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mapped' => false,
            'error_bubbling' => false,
            'field_type' => NumberType::class,
            'scale' => 15,
            'x_options' => [],
            'y_options' => [],
        ]);
    }

    /**
     * Get the property path.
     *
     * @param FormEvent $event The form event
     *
     * @return string
     */
    private function getPropertyPath(FormEvent $event): string
    {
        $propertyPath = $event->getForm()->getConfig()->getOption('property_path');

        if (null === $propertyPath) {
            $propertyPath = $event->getForm()->getName();
        }

        return $propertyPath;
    }
}
