<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Phone\Form\Type;

use Klipper\Component\Phone\Form\DataTransformer\PhoneNumberToArrayTransformer;
use Klipper\Component\Phone\Form\DataTransformer\PhoneNumberToStringTransformer;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PhoneType extends AbstractType
{
    public const WIDGET_SINGLE_TEXT = 'single_text';
    public const WIDGET_COUNTRY_CHOICE = 'country_choice';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (self::WIDGET_COUNTRY_CHOICE === $options['widget']) {
            $this->buildFormChoice($builder, $options);
        } else {
            $this->buildFormText($builder, $options);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars = array_merge($view->vars, [
            'widget' => $options['widget'],
            'type' => $this->getBlockPrefix(),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'widget' => self::WIDGET_SINGLE_TEXT,
            'compound' => static function (Options $options) {
                return self::WIDGET_SINGLE_TEXT !== $options['widget'];
            },
            'default_region' => PhoneNumberUtil::UNKNOWN_REGION,
            'format' => PhoneNumberFormat::INTERNATIONAL,
            'invalid_message' => 'This value is not a valid phone number.',
            'by_reference' => false,
            'error_bubbling' => false,
            'default_country' => null,
            'country_options' => [],
            'number_options' => [],
            'format_country_labels' => static function ($name, $prefix) {
                return sprintf('%s (+%s)', $name, $prefix);
            },
        ]);

        $resolver->setAllowedTypes('default_country', ['null', 'string']);
        $resolver->setAllowedTypes('country_options', 'array');
        $resolver->setAllowedTypes('number_options', 'array');

        $resolver->setAllowedValues('widget', [
            self::WIDGET_SINGLE_TEXT,
            self::WIDGET_COUNTRY_CHOICE,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'phone';
    }

    /**
     * Builds the form with country choice form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    protected function buildFormChoice(FormBuilderInterface $builder, array $options): void
    {
        $countryChoices = $this->buildCountryChoices($options);
        $countryOptions = $this->buildDefaultOptions($options, $options['country_options'], [
            'choice_translation_domain' => false,
            'required' => true,
            'choices' => $countryChoices,
        ]);
        $numberOptions = $this->buildDefaultOptions($options, $options['number_options']);

        $builder
            ->add('country', ChoiceType::class, $countryOptions)
            ->add('number', TextType::class, $numberOptions)
            ->addViewTransformer(new PhoneNumberToArrayTransformer($countryChoices, $options['default_country']))
        ;
    }

    /**
     * Builds the form without country choice form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    protected function buildFormText(FormBuilderInterface $builder, array $options): void
    {
        $builder->addViewTransformer(
            new PhoneNumberToStringTransformer($options['default_region'], $options['format'])
        );
    }

    /**
     * Build the form options.
     *
     * @param array $formOptions This form options
     * @param array $options     The form options of the new form
     * @param array $other       The other form options
     */
    private function buildDefaultOptions(array $formOptions, array $options, array $other = []): array
    {
        $defaultOptions = [
            'error_bubbling' => true,
            'required' => $formOptions['required'],
            'disabled' => $formOptions['disabled'],
            'translation_domain' => $formOptions['translation_domain'],
        ];

        return array_merge($defaultOptions, $other, $options);
    }

    /**
     * Build the country choices.
     *
     * @param array $options The form options
     */
    private function buildCountryChoices(array $options): array
    {
        $util = PhoneNumberUtil::getInstance();
        $formatter = $options['format_country_labels'];
        $countries = [];
        $countryChoices = [];

        if (isset($options['country_options']['choices'])
                && \is_array($options['country_options']['choices'])) {
            foreach ($options['country_options']['choices'] as $country) {
                $code = $util->getCountryCodeForRegion($country);

                if ($code) {
                    $countries[$country] = $code;
                }
            }
        }

        if (empty($countries)) {
            foreach ($util->getSupportedRegions() as $country) {
                $countries[$country] = $util->getCountryCodeForRegion($country);
            }
        }

        foreach (Countries::getNames() as $region => $name) {
            if (!isset($countries[$region])) {
                continue;
            }

            $countryChoices[$formatter($name, $countries[$region])] = $region;
        }

        return $countryChoices;
    }
}
