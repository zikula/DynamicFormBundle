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

namespace Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicOptions;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MoneyFormOptionsArrayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('currency', TextType::class, [
            'empty_data' => 'EUR',
            'label' => 'Currency',
            'required' => false,
            'help' => 'Any 3 letter ISO 4217 code. Default: EUR',
        ]);
    }

    public function getParent(): ?string
    {
        return FormOptionsArrayType::class;
    }
}
