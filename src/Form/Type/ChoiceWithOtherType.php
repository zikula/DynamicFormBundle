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
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer\ChoiceWithOtherValueTransformer;

class ChoiceWithOtherType extends AbstractType
{
    const OTHER_VALUE = 'other';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // http://symfony.com/doc/master/form/create_custom_field_type.html
        // prepare passed $options
        unset($options['other_label']);
        $options['attr']['class'] = 'other-enabler';

        $builder
            ->add('choices', ChoiceType::class, $options)
            ->add('other', TextType::class);

        $builder->addModelTransformer(new ChoiceWithOtherValueTransformer($options));

        // constraints can be added in listener
//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            // http://symfony.com/doc/current/form/dynamic_form_modification.html
            // ... adding the constraint if needed
//        });

        // probably need a DataMapper to map `other` field back to data and select the 'other' option.
        // https://symfony.com/doc/current/form/data_mappers.html
        // implements DataMapperInterface
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // if needed
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [],
            'expanded' => false,
            'multiple' => false,
            'other_label' => null,
        ]);
        $resolver->setRequired('choices');
        $resolver->addAllowedTypes('choices', 'array');
        $resolver->addAllowedTypes('other_label', ['null', 'string']);
        // validate if 'other' selected, then other field cannot be empty
        $resolver->addNormalizer('choices', function (Options $options, $value) {
            $label = $options['other_label'] ?? 'Other';
            $value[$label] = self::OTHER_VALUE;

            return $value;
        }, true);
    }
}
