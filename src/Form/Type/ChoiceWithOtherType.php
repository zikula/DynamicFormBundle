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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceWithOtherType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // http://symfony.com/doc/master/form/create_custom_field_type.html
        // prepare passed $options

        $builder
            ->add('choice', ChoiceType::class, $options)
            ->add('other', TextType::class, $options);

        // this will requires also custom ModelTransformer
        // http://symfony.com/doc/current/form/data_transformers.html
//        $builder->addModelTransformer($transformer);
        // add 'other' choice to choicelist

        // constraints can be added in listener
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            // http://symfony.com/doc/current/form/dynamic_form_modification.html
            // ... adding the constraint if needed
        });

    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // if needed
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // validate if 'other' selected, then other field cannot be empty
    }
}
