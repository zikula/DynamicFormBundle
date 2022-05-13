<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\Bundle\DynamicFormPropertyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\WeekType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\DynamicFormPropertyBundle\DynamicPropertyInterface;
use Zikula\Bundle\DynamicFormPropertyBundle\Event\FormTypeChoiceEvent;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer\ChoiceValuesTransformer;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer\RegexConstraintTransformer;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicOptions\ChoiceFormOptionsArrayType;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicOptions\DateTimeFormOptionsArrayType;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicOptions\FormOptionsArrayType;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicOptions\MoneyFormOptionsArrayType;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicOptions\RegexibleFormOptionsArrayType;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Data\FormTypesChoices;

/**
 * Form type providing a dynamic selection of field type and field options.
 */
class DynamicFieldType extends AbstractType
{
    private TranslatorInterface $translator;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        TranslatorInterface $translator,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Field name',
                'help' => 'The name can only contain letters and underscores. For property access and internal use.'
            ])
            ->add('formType', ChoiceType::class, [
                'label' => 'Field type',
                'attr' => ['class' => 'dynamic-property-form-type-select'],
                'choices' => $this->getChoices(),
                'placeholder' => 'Select'
            ])
            ->add('formOptions', FormOptionsArrayType::class, [
                'label' => 'Field options',
                'auto_initialize' => false
            ])
            ->add('weight', IntegerType::class, [
                'empty_data' => '0',
                'required' => false
            ])
            ->add('active', CheckboxType::class, [
                'required' => false
            ])
        ;

        $formModifier = function (FormInterface $form, $formType = null) use ($builder) {
            switch ($formType) {
                case ChoiceType::class:
                case ChoiceWithOtherType::class:
                    $optionsType = ChoiceFormOptionsArrayType::class;
                    break;
                case DateType::class:
                case DateTimeType::class:
                case TimeType::class:
                case BirthdayType::class:
                    $optionsType = DateTimeFormOptionsArrayType::class;
                    break;
                case MoneyType::class:
                    $optionsType = MoneyFormOptionsArrayType::class;
                    break;
                case TextType::class:
                case TextareaType::class:
                    $optionsType = RegexibleFormOptionsArrayType::class;
                    break;
                default:
                    $optionsType = FormOptionsArrayType::class;
            }
            $formOptions = $builder->create('formOptions', $optionsType, [
                'label' => 'Field options',
                'auto_initialize' => false
            ]);
            if (ChoiceFormOptionsArrayType::class === $optionsType) {
                $formOptions->get('choices')->addModelTransformer(
                    new ChoiceValuesTransformer()
                );
            } elseif (RegexibleFormOptionsArrayType::class === $optionsType) {
                $formOptions->get('constraints')->addModelTransformer(
                    new RegexConstraintTransformer()
                );
            }
            $form->add($formOptions->getForm());
        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, static function (FormEvent $event) use ($formModifier) {
            $data = $event->getData();
            if ($data instanceof DynamicPropertyInterface) {
                $formModifier($event->getForm(), $data->getFormType());
            }
        });
        $builder->get('formType')->addEventListener(FormEvents::POST_SUBMIT, static function (FormEvent $event) use ($formModifier) {
            $formType = $event->getForm()->getData();
            $formModifier($event->getForm()->getParent(), $formType);
        });
    }

    private function getChoices(): FormTypesChoices
    {
        $choices = new FormTypesChoices([
            $this->trans('Text fields') => [
                $this->trans('Text') => TextType::class,
                $this->trans('Textarea') => TextareaType::class,
                $this->trans('Email') => EmailType::class,
                $this->trans('Integer') => IntegerType::class,
                $this->trans('Money') => MoneyType::class,
                $this->trans('Number') => NumberType::class,
                $this->trans('Password') => PasswordType::class,
                $this->trans('Percent') => PercentType::class,
                $this->trans('Url') => UrlType::class,
                $this->trans('Range') => RangeType::class,
                $this->trans('Phone number') => TelType::class,
            ],
            $this->trans('Choice fields') => [
                $this->trans('Choice') => ChoiceType::class,
                $this->trans('Choice with other') => ChoiceWithOtherType::class,
                $this->trans('Checkbox') => CheckboxType::class,
                // $this->trans('Radio') => RadioType::class,
                $this->trans('Country') => CountryType::class,
                $this->trans('Language') => LanguageType::class,
                $this->trans('Locale') => LocaleType::class,
                $this->trans('Timezone') => TimezoneType::class,
                $this->trans('Currency') => CurrencyType::class,
            ],
            $this->trans('Date and time fields') => [
                $this->trans('Date') => DateType::class,
                $this->trans('DateTime') => DateTimeType::class,
                $this->trans('Time') => TimeType::class,
                $this->trans('Birthday') => BirthdayType::class,
                $this->trans('Week number') => WeekType::class,
            ],
            $this->trans('Other fields') => [
                $this->trans('File') => FileType::class,
            ]
        ]);

        $this->eventDispatcher->dispatch(new FormTypeChoiceEvent($choices));

        return $choices;
    }

    private function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
