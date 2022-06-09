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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Zikula\Bundle\DynamicFormPropertyBundle\Entity\AbstractDynamicPropertySpecification;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\ChoiceTypeTransformed;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\ChoiceWithOtherType;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicFieldType;

class DynamicFieldTypeTest extends TypeTestCase
{
    /**
     * @return FormExtensionInterface[]
     */
    protected function getExtensions(): array
    {
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $type = new DynamicFieldType($dispatcher, false);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    /**
     * @param string[] $expectedOptions
     * @covers \Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\DynamicFieldType
     * @dataProvider data
     */
    public function testProperFormCreation(string $type, array $expectedOptions): void
    {
        $formData = new class() extends AbstractDynamicPropertySpecification {
        };
        $formData->setName('foo');
        $formData->setFormType($type);
        $form = $this->factory->create(DynamicFieldType::class, $formData);

        // check basic types exist
        $this->assertTrue($form->has('name'));
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
        yield 'default' => [IntegerType::class, []];
    }
}
