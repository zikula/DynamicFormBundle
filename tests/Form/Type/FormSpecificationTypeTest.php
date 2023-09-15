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
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Translation\IdentityTranslator;
use Zikula\Bundle\DynamicFormBundle\Entity\AbstractFormSpecification;
use Zikula\Bundle\DynamicFormBundle\EventSubscriber\FormTypeChoiceEventSubscriber;
use Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceTypeTransformed;
use Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceWithOtherType;
use Zikula\Bundle\DynamicFormBundle\Form\Type\FormSpecificationType;

class FormSpecificationTypeTest extends TypeTestCase
{
    /**
     * @return FormExtensionInterface[]
     */
    protected function getExtensions(): array
    {
        $dispatcher = new EventDispatcher();
        $translator = new IdentityTranslator();
        $dispatcher->addSubscriber(new FormTypeChoiceEventSubscriber($translator));
        $type = new FormSpecificationType($dispatcher, false);

        return [
            new PreloadedExtension([$type], []),
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

        $this->assertTrue($form->has('name'));
        $this->assertFalse($form->get('name')->isDisabled());

        $this->assertInstanceOf(TextType::class, $form->get('labels')->getConfig()->getType()->getInnerType());
        $this->assertInstanceOf(TextType::class, $form->get('groups')->getConfig()->getType()->getInnerType());
        $this->assertCount(0, $form->get('labels'));
        $this->assertCount(0, $form->get('groups'));
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Entity\AbstractFormSpecification
     */
    public function testBasicFormSubmission(): void
    {
        $formData = new class() extends AbstractFormSpecification {
        };
        $form = $this->factory->create(FormSpecificationType::class, $formData);
        $form->submit(['name' => 'foo', 'formType' => IntegerType::class, 'active' => true]);
        $this->assertTrue($form->isSynchronized());
        $this->assertEmpty($form->getErrors());
        /** @var AbstractFormSpecification $data */
        $data = $form->getData();
        $this->assertTrue($data->isActive());
        $this->assertSame('foo', $data->getName());
        $this->assertSame(IntegerType::class, $data->getFormType());
        $this->assertArrayHasKey('required', $data->getFormOptions());
    }

    /**
     * @param string[] $expectedOptions
     *
     * @covers \Zikula\Bundle\DynamicFormBundle\Entity\AbstractFormSpecification
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceTypeTransformed
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceWithOtherType
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\FormSpecificationType
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\EventListener\AddFormOptionsListener
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\DynamicOptions\ChoiceFormOptionsArrayType
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\DynamicOptions\CountryFormOptionsArrayType
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\DynamicOptions\DateTimeFormOptionsArrayType
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\DynamicOptions\FormOptionsArrayType
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\DynamicOptions\LanguageFormOptionsArrayType
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\DynamicOptions\MoneyFormOptionsArrayType
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\DynamicOptions\RangeFormOptionsArrayType
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\DynamicOptions\RegexibleFormOptionsArrayType
     *
     * @dataProvider data
     */
    public function testProperFormCreation(string $type, array $expectedOptions): void
    {
        $formData = new class() extends AbstractFormSpecification {
        };
        $formData->setName('foo');
        $formData->setFormType($type);
        $form = $this->factory->create(FormSpecificationType::class, $formData);

        // check basic types exist
        $this->assertTrue($form->has('name'));
        $this->assertTrue($form->get('name')->isDisabled());
        $this->assertTrue($form->has('formType'));
        $this->assertTrue($form->has('labels'));
        $this->assertTrue($form->has('active'));
        $this->assertTrue($form->has('groups'));
        $this->assertTrue($form->has('formOptions'));

        // check types added by eventSubscribers exist
        $expectedOptions = array_merge($expectedOptions, ['required', 'priority', 'help']); // standard options
        foreach ($expectedOptions as $expectedField) {
            $this->assertTrue($form->get('formOptions')->has($expectedField));
        }
        $this->assertCount(count($expectedOptions), $form->get('formOptions')); // no others exist

        $this->assertTrue($form->isSynchronized());
    }

    public function data(): \Iterator
    {
        yield 'choice' => [ChoiceTypeTransformed::class, ['multiple', 'expanded', 'choices']];
        yield 'choiceWithOther' => [ChoiceWithOtherType::class, ['multiple', 'expanded', 'choices']];
        yield 'date' => [DateType::class, ['html5', 'widget', 'input', 'format', 'model_timezone']];
        yield 'datetime' => [DateTimeType::class, ['html5', 'widget', 'input', 'format', 'model_timezone']];
        yield 'time' => [TimeType::class, ['html5', 'widget', 'input', 'format', 'model_timezone']];
        yield 'birthday' => [BirthdayType::class, ['html5', 'widget', 'input', 'format', 'model_timezone']];
        yield 'money' => [MoneyType::class, ['currency']];
        yield 'text' => [TextType::class, ['constraints']];
        yield 'textarea' => [TextareaType::class, ['constraints']];
        yield 'country' => [CountryType::class, ['alpha3']];
        yield 'language' => [LanguageType::class, ['alpha3']];
        yield 'range' => [RangeType::class, ['attr']];
        yield 'default' => [IntegerType::class, []];
    }
}
