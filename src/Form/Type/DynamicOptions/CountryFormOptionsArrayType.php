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
use Symfony\Component\Form\FormBuilderInterface;

class CountryFormOptionsArrayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('alpha3', CheckboxType::class, [
            'label' => 'Use ISO 3166-1 alpha-3',
            'help' => 'If checked, <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3">ISO 3166-1 alpha-3</a> codes (e.g. NZL) will be used instead of <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">alpha-2</a> (NZ).',
            'help_html' => true,
        ]);
    }

    public function getParent(): ?string
    {
        return FormOptionsArrayType::class;
    }
}
