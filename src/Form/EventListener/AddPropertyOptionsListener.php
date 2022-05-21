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

namespace Zikula\Bundle\DynamicFormPropertyBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Zikula\Bundle\DynamicFormPropertyBundle\DynamicPropertySpecificationInterface;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer\ChoiceValuesTransformer;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer\RegexConstraintTransformer;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\ChoiceWithOtherType;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicOptions\ChoiceFormOptionsArrayType;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicOptions\DateTimeFormOptionsArrayType;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicOptions\FormOptionsArrayType;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicOptions\MoneyFormOptionsArrayType;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicOptions\RegexibleFormOptionsArrayType;

class AddPropertyOptionsListener implements EventSubscriberInterface
{
    private FormBuilderInterface $formBuilder;

    public function __construct(FormBuilderInterface $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    public function onPreSetData(FormEvent $event): void
    {
        $data = $event->getData();
        if ($data instanceof DynamicPropertySpecificationInterface) {
            $this->addFormOptions($event->getForm(), $data->getFormType());
        }
    }

    public function onPostSubmit(FormEvent $event): void
    {
        if ('formType' !== $event->getForm()->getName()) {
            return;
        }
        $formType = $event->getForm()->getData();
        $this->addFormOptions($event->getForm()->getParent(), $formType);
    }

    protected function addFormOptions(FormInterface $form, ?string $formType = null): void
    {
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
        $formOptions = $this->formBuilder->create('formOptions', $optionsType, [
            'label' => 'Field options',
            'auto_initialize' => false,
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
    }
}
