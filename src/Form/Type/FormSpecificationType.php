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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Zikula\Bundle\DynamicFormBundle\Entity\AbstractFormSpecification;
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
            ->add('formType', ChoiceType::class, [
                'priority' => 90,
                'label' => 'Field type',
                'attr' => ['class' => 'dynamic-property-form-type-select'],
                'choices' => $choiceEvent->getChoices(),
                'placeholder' => 'Select',
            ])
        ;
        if ($this->translateLabels) {
            $builder->add('labels', TranslationCollectionType::class, [
                'priority' => 85,
                'label' => 'Translated labels',
                'help' => 'If the label field is left blank, the name field will be used instead.',
            ]);
            $builder->add('groups', TranslationCollectionType::class, [
                'priority' => 80,
                'label' => 'Translated group names',
            ]);
        } else {
            $builder->add('labels', TextType::class, [
                'priority' => 85,
                'label' => 'Label',
                'required' => false,
                'help' => 'If the label field is left blank, the name field will be used instead.',
            ]);
            $builder->get('labels')->addModelTransformer(new DefaultLabelToLabelsTransformer());
            $builder->add('groups', TextType::class, [
                'priority' => 80,
                'label' => 'Group',
                'required' => false,
            ]);
            $builder->get('groups')->addModelTransformer(new DefaultLabelToLabelsTransformer());
        }

        $builder
            ->add('formOptions', FormOptionsArrayType::class, [
                'priority' => 75,
                'label' => 'Field options',
                'auto_initialize' => false,
            ])
            ->add('active', CheckboxType::class, [
                'priority' => 95,
                'required' => false,
            ])
        ;
        $listener = new AddFormOptionsListener($builder);
        $builder->addEventSubscriber($listener);
        $builder->get('formType')->addEventSubscriber($listener);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            /** @var ?AbstractFormSpecification $data */
            $data = $event->getData();
            $event->getForm()->add('name', TextType::class, [
                'disabled' => (null !== $data && !empty($data->getName())),
                'priority' => 100,
                'label' => 'Field name',
                'help' => 'The name can only contain letters and underscores.<br>(For property access and internal use. This cannot be edited after initial creation.)',
                'help_html' => true,
            ]);
        });
    }
}
