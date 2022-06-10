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

namespace Zikula\Bundle\DynamicFormBundle\Form\Type\DynamicOptions;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ChoiceFormOptionsArrayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('multiple', CheckboxType::class, [
                'label' => 'Multiple',
                'required' => false,
            ])
            ->add('expanded', CheckboxType::class, [
                'label' => 'Expanded',
                'required' => false,
            ])
            ->add('choices', TextType::class, [
                'label' => 'Choices',
                'help' => 'A comma-delineated list. either "value, value, value" or "value:label, value:label, value:label"',
            ])
        ;
    }

    public function getParent(): ?string
    {
        return FormOptionsArrayType::class;
    }
}
