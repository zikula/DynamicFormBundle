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

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Translation\IdentityTranslator;
use Zikula\Bundle\DynamicFormBundle\Entity\AbstractFormSpecification;
use Zikula\Bundle\DynamicFormBundle\EventSubscriber\FormTypeChoiceEventSubscriber;
use Zikula\Bundle\DynamicFormBundle\Form\Type\FormSpecificationType;
use Zikula\Bundle\DynamicFormBundle\Form\Type\TranslationCollectionType;
use Zikula\Bundle\DynamicFormBundle\Provider\LocaleProviderInterface;

class FormSpecificationTypeTranslatedTest extends TypeTestCase
{
    /**
     * @return FormExtensionInterface[]
     */
    protected function getExtensions(): array
    {
        $dispatcher = new EventDispatcher();
        $translator = new IdentityTranslator();
        $localeProvider = new class() implements LocaleProviderInterface {
            public function getSupportedLocales(): array
            {
                return ['en', 'de'];
            }

            public function getSupportedLocaleNames(?string $displayLocale = null): array
            {
                return ['English' => 'en', 'German' => 'de'];
            }
        };

        $dispatcher->addSubscriber(new FormTypeChoiceEventSubscriber($translator));
        $formSpecificationType = new FormSpecificationType($dispatcher, true);
        $translationCollectionType = new TranslationCollectionType($localeProvider);

        return [
            new PreloadedExtension([$formSpecificationType, $translationCollectionType], []),
        ];
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Entity\AbstractFormSpecification
     */
    public function testFormCreation(): void
    {
        $formData = new class() extends AbstractFormSpecification {
        };
        $form = $this->factory->create(FormSpecificationType::class, $formData);
        $this->assertInstanceOf(TranslationCollectionType::class, $form->get('labels')->getConfig()->getType()->getInnerType());
        $this->assertInstanceOf(TranslationCollectionType::class, $form->get('groups')->getConfig()->getType()->getInnerType());
        $this->assertCount(2, $form->get('labels'));
        $this->assertCount(2, $form->get('groups'));
        $this->assertTrue($form->get('labels')->has('en'));
        $this->assertTrue($form->get('labels')->has('de'));
        $this->assertTrue($form->get('groups')->has('en'));
        $this->assertTrue($form->get('groups')->has('de'));
    }
}
