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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\DynamicFormBundle\Container\SpecificationContainerInterface;

/**
 * Form type for embedding dynamic fields.
 */
class DynamicFieldsType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        /** @var SpecificationContainerInterface $specificationContainer */
        $specificationContainer = $options['specificationContainer'];

        foreach ($specificationContainer->getFormSpecifications() as $fieldSpecification) {
            $fieldOptions = $fieldSpecification->getFormOptions();
            $fieldOptions['label'] = $fieldOptions['label'] ?? $fieldSpecification->getLabel($this->translator->getLocale());
            $builder->add($fieldSpecification->getName(), $fieldSpecification->getFormType(), $fieldOptions);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [],
            'mapped' => false,
            'inherit_data' => true,
        ]);
        $resolver->setRequired('specificationContainer');
        $resolver->addAllowedTypes('specificationContainer', SpecificationContainerInterface::class);
    }
}
