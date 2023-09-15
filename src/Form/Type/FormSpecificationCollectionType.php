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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Bundle\DynamicFormBundle\Entity\AbstractFormSpecification;

class FormSpecificationCollectionType extends AbstractType
{
    public function getParent(): ?string
    {
        return CollectionType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entry_type' => FormSpecificationType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => function (?AbstractFormSpecification $property = null) {
                return null === $property || empty($property->getName());
            },
            'prototype' => true, // required for javascript to work
            'by_reference' => false, // required to force use of add/remove methods in Survey
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'zikula_dynamic_field_collection';
    }
}
