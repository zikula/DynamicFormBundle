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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Bundle\DynamicFormBundle\Form\DataMapper\ChoiceWithOtherDataMapper;

class ChoiceWithOtherType extends AbstractType
{
    public const OTHER_VALUE = 'other';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('choices', ChoiceType::class, [
                'choices' => $options['choices'],
                'multiple' => $options['multiple'],
                'expanded' => $options['expanded'],
                'required' => $options['required'],
                'empty_data' => '',
                'attr' => ['class' => 'other-enabler'],
            ])
            ->add('other', TextType::class, [
                'empty_data' => '',
                'required' => false,
            ])
            ->setDataMapper(new ChoiceWithOtherDataMapper())
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $this->validate($event);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [],
            'expanded' => false,
            'multiple' => false,
            'empty_data' => null,
            'other_label' => null,
        ]);
        $resolver->setRequired('choices');
        $resolver->addAllowedTypes('choices', 'array');
        $resolver->addAllowedTypes('other_label', ['null', 'string']);
        // add the 'other' option to the choice list
        $resolver->addNormalizer('choices', function (Options $options, $value) {
            $label = $options['other_label'] ?? 'Other';
            $value[$label] = self::OTHER_VALUE;

            return $value;
        }, true);
    }

    private function validate(FormEvent $event): void
    {
        $data = $event->getData();
        if (empty($data)) {
            return;
        }
        if (empty($data['other']) && isset($data['choices'])
            && (
                (is_array($data['choices']) && in_array(self::OTHER_VALUE, $data['choices'], true))
                || (self::OTHER_VALUE === $data['choices'])
            )
        ) {
            $errorMessage = 'If you select "other" you must indicate a value';
            $event->getForm()->addError(new FormError($errorMessage, $errorMessage, [], null));
        }
    }
}
