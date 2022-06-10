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

namespace Zikula\Bundle\DynamicFormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Zikula\Bundle\DynamicFormBundle\Event\FormTypeChoiceEvent;
use Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\DefaultLabelToLabelsTransformer;
use Zikula\Bundle\DynamicFormBundle\Form\EventListener\AddFormOptionsListener;
use Zikula\Bundle\DynamicFormBundle\Form\Type\DynamicOptions\FormOptionsArrayType;

/**
 * Form type defining a form field type and field options.
 */
class FormSpecificationType extends AbstractType
{
    private EventDispatcherInterface $eventDispatcher;
    private bool $translateLabels;

    public function __construct(EventDispatcherInterface $eventDispatcher, bool $translateLabels = false)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translateLabels = $translateLabels;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->eventDispatcher->dispatch($choiceEvent = new FormTypeChoiceEvent());

        $builder
            ->add('name', TextType::class, [
                'label' => 'Field name',
                'help' => 'The name can only contain letters and underscores. For property access and internal use.',
            ])
            ->add('formType', ChoiceType::class, [
                'label' => 'Field type',
                'attr' => ['class' => 'dynamic-property-form-type-select'],
                'choices' => $choiceEvent->getChoices(),
                'placeholder' => 'Select',
            ])
        ;
        if ($this->translateLabels) {
            $builder->add('labels', TranslationCollectionType::class, [
                'label' => 'Translated labels',
                'help' => 'If the label field is left blank, the name field will be used instead.',
            ]);
            $builder->add('groups', TranslationCollectionType::class, [
                'label' => 'Translated group names',
            ]);
        } else {
            $builder->add('labels', TextType::class, [
                'label' => 'Label',
                'required' => false,
                'help' => 'If the label field is left blank, the name field will be used instead.',
            ]);
            $builder->get('labels')->addModelTransformer(new DefaultLabelToLabelsTransformer());
            $builder->add('groups', TextType::class, [
                'label' => 'Group',
                'required' => false,
            ]);
            $builder->get('groups')->addModelTransformer(new DefaultLabelToLabelsTransformer());
        }

        $builder
            ->add('formOptions', FormOptionsArrayType::class, [
                'label' => 'Field options',
                'auto_initialize' => false,
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
            ])
        ;
        $listener = new AddFormOptionsListener($builder);
        $builder->addEventSubscriber($listener);
        $builder->get('formType')->addEventSubscriber($listener);
    }
}
