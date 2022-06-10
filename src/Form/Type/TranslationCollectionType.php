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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Zikula\Bundle\DynamicFormBundle\Provider\LocaleProviderInterface;

class TranslationCollectionType extends AbstractType
{
    private LocaleProviderInterface $localeProvider;

    public function __construct(LocaleProviderInterface $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->localeProvider->getSupportedLocaleNames() as $name => $value) {
            $builder->add($value, TextType::class, [
                'label' => $name,
                'required' => false,
            ]);
        }
    }
}
