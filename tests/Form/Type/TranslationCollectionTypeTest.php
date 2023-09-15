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

namespace Zikula\Bundle\DynamicFormBundle\Tests\Form\Type;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Zikula\Bundle\DynamicFormBundle\Form\Type\TranslationCollectionType;
use Zikula\Bundle\DynamicFormBundle\Provider\LocaleProviderInterface;

class TranslationCollectionTypeTest extends TypeTestCase
{
    private LocaleProviderInterface $localeProvider;

    protected function setUp(): void
    {
        $this->localeProvider = new class() implements LocaleProviderInterface {
            public function getSupportedLocales(): array
            {
                return ['en'];
            }

            public function getSupportedLocaleNames(?string $displayLocale = null): array
            {
                return ['English' => 'en'];
            }
        };
        parent::setUp();
    }

    /**
     * @return PreloadedExtension[]
     */
    protected function getExtensions(): array
    {
        $type = new TranslationCollectionType($this->localeProvider);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\TranslationCollectionType
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'en' => 'myEnglishLabel',
        ];

        $form = $this->factory->create(TranslationCollectionType::class, $formData);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $data = $form->getData();
        $expected = ['en' => 'myEnglishLabel'];
        $this->assertEquals($expected, $data);
    }
}
