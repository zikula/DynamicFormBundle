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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DynamicFieldCollectionType extends AbstractType
{
    public function getParent()
    {
        return CollectionType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entry_type' => DynamicFieldType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true, // required for javascript to work
            'by_reference' => false // required to force use of add/remove methods in Survey
        ]);
    }

    public function getBlockPrefix()
    {
        return 'zikula_dynamic_field_collection';
    }
}