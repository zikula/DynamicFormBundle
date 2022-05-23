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

namespace Zikula\Bundle\DynamicFormPropertyBundle\Tests\Form\Type;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\TranslationCollectionType;
use Zikula\Bundle\DynamicFormPropertyBundle\Provider\LocaleProviderInterface;

class TranslationCollectionTypeTest extends TypeTestCase
{
    private $localeProvider;


    // https://symfony.com/doc/current/form/unit_testing.html


//    protected function setUp(): void
//    {
//        $this->localeProvider = $this->getMockBuilder(LocaleProviderInterface::class)
//            ->getMock()
//            ->method('getSupportedLocaleNames')
//            ->willReturn([])
//        ;

//        parent::setUp();
//    }

//    protected function getExtensions()
//    {
//        $type = new TranslationCollectionType($this->localeProvider);
//
//        return [
//            new PreloadedExtension([$type], []),
//        ];
//    }

    public function testSubmitValidData(): void
    {
        $this->markTestSkipped('not ready!');
        $formData = [
            'test' => 'test',
            'test2' => 'test2',
        ];

        $model = [];
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(TranslationCollectionType::class, $model);

        $expected = [];
        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        $this->assertEquals($expected, $model);
    }

    public function testCustomFormView(): void
    {
        $this->markTestSkipped('not ready!');
        $formData = [];
        // ... prepare the data as you need

        // The initial data may be used to compute custom view variables
        $view = $this->factory->create(TranslationCollectionType::class, $formData)
            ->createView();

        $this->assertArrayHasKey('custom_var', $view->vars);
        $this->assertSame('expected value', $view->vars['custom_var']);
    }
}