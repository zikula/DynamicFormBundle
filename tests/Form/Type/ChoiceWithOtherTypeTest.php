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

use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceWithOtherType;

/**
 * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceWithOtherType
 * @covers \Zikula\Bundle\DynamicFormBundle\Form\DataMapper\ChoiceWithOtherDataMapper
 */
class ChoiceWithOtherTypeTest extends TypeTestCase
{
    /**
     * @var array<string, mixed>
     */
    private array $options = [
        'choices' => [
            'Spades' => 'spades',
            'Diamonds' => 'diamonds',
            'Hearts' => 'hearts',
            'Clubs' => 'clubs',
        ],
        'multiple' => false,
        'expanded' => false,
    ];

    /**
     * @return AbstractExtension[]
     */
    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testOtherOptionExists(): void
    {
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $choices = $form->get('choices')->getConfig()->getOption('choices');
        $this->assertCount(5, $choices);
        $this->assertContains(ChoiceWithOtherType::OTHER_VALUE, $choices);

        $this->options['expanded'] = true;
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $choices = $form->get('choices')->getConfig()->getOption('choices');
        $this->assertCount(5, $choices);
        $this->assertContains(ChoiceWithOtherType::OTHER_VALUE, $choices);

        $this->options['expanded'] = false;
        $this->options['multiple'] = true;
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $choices = $form->get('choices')->getConfig()->getOption('choices');
        $this->assertCount(5, $choices);
        $this->assertContains(ChoiceWithOtherType::OTHER_VALUE, $choices);

        $this->options['expanded'] = true;
        $this->options['multiple'] = true;
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $choices = $form->get('choices')->getConfig()->getOption('choices');
        $this->assertCount(5, $choices);
        $this->assertContains(ChoiceWithOtherType::OTHER_VALUE, $choices);
    }

    public function testFormValidation(): void
    {
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $form->submit(['choices' => ChoiceWithOtherType::OTHER_VALUE, 'other' => '']);
        $this->assertTrue($form->isSynchronized());
        $this->assertNotEmpty($form->getErrors());
        $this->assertCount(1, $form->getErrors());
        foreach ($form->getErrors() as $error) {
            $this->assertEquals('If you select "other" you must indicate a value', $error->getMessage());
        }

        $this->options['expanded'] = true;
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $form->submit(['choices' => ChoiceWithOtherType::OTHER_VALUE, 'other' => '']);
        $this->assertTrue($form->isSynchronized());
        $this->assertNotEmpty($form->getErrors());
        $this->assertCount(1, $form->getErrors());
        foreach ($form->getErrors() as $error) {
            $this->assertEquals('If you select "other" you must indicate a value', $error->getMessage());
        }

        $this->options['expanded'] = false;
        $this->options['multiple'] = true;
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $form->submit(['choices' => [ChoiceWithOtherType::OTHER_VALUE], 'other' => '']);
        $this->assertTrue($form->isSynchronized());
        $this->assertNotEmpty($form->getErrors());
        $this->assertCount(1, $form->getErrors());
        foreach ($form->getErrors() as $error) {
            $this->assertEquals('If you select "other" you must indicate a value', $error->getMessage());
        }

        $this->options['expanded'] = true;
        $this->options['multiple'] = true;
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $form->submit(['choices' => [ChoiceWithOtherType::OTHER_VALUE], 'other' => '']);
        $this->assertTrue($form->isSynchronized());
        $this->assertNotEmpty($form->getErrors());
        $this->assertCount(1, $form->getErrors());
        foreach ($form->getErrors() as $error) {
            $this->assertEquals('If you select "other" you must indicate a value', $error->getMessage());
        }
    }

    /**
     * @param array<int, array<string, string>> $formData
     *
     * @dataProvider data
     */
    public function testSubmitValidData(string $expected, array $formData): void
    {
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertEquals($expected, $data);
    }

    /**
     * @param array<int, array<string, string>> $formData
     *
     * @dataProvider data
     */
    public function testExpandedSubmitValidData(string $expected, array $formData): void
    {
        $this->options['expanded'] = true;
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $data = $form->getData();
        $this->assertEquals($expected, $data);
    }

    public function data(): \Iterator
    {
        yield 0 => ['', ['choices' => '', 'other' => '']];
        yield 1 => ['spades', ['choices' => 'spades', 'other' => '']];
        yield 2 => ['Hearts', ['choices' => ChoiceWithOtherType::OTHER_VALUE, 'other' => 'Hearts']]; // case-sensitive
        yield 3 => ['stars', ['choices' => ChoiceWithOtherType::OTHER_VALUE, 'other' => 'stars']]; // non-value
    }

    /**
     * @param array<int, array<string, mixed>> $formData
     *
     * @dataProvider multipleData
     */
    public function testMultipleSubmitValidData(string $expected, array $formData): void
    {
        $this->options['multiple'] = true;
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $data = $form->getData();
        $this->assertEquals($expected, $data);
    }

    /**
     * @param array<int, array<string, mixed>> $formData
     *
     * @dataProvider multipleData
     */
    public function testExpandedMultipleSubmitValidData(string $expected, array $formData): void
    {
        $this->options['multiple'] = true;
        $this->options['expanded'] = true;
        $form = $this->factory->create(ChoiceWithOtherType::class, [], $this->options);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $data = $form->getData();
        $this->assertEquals($expected, $data);
    }

    public function multipleData(): \Iterator
    {
        yield 1 => ['', ['choices' => [], 'other' => '']];
        yield 2 => ['spades,hearts', ['choices' => ['spades', 'hearts'], 'other' => '']];
        yield 3 => ['spades,hearts,moons', ['choices' => ['spades', 'hearts', ChoiceWithOtherType::OTHER_VALUE], 'other' => 'moons']];
        yield 4 => ['hearts,Spades', ['choices' => ['hearts', ChoiceWithOtherType::OTHER_VALUE], 'other' => 'Spades']];
        yield 5 => ['stars', ['choices' => [ChoiceWithOtherType::OTHER_VALUE], 'other' => 'stars']];
        yield 5 => ['stars,moons', ['choices' => [ChoiceWithOtherType::OTHER_VALUE], 'other' => 'stars,moons']];
    }

    public function testLoadExistingData(): void
    {
        $form = $this->factory->create(ChoiceWithOtherType::class, 'hearts', $this->options);
        $form->submit(['choices' => 'hearts']);
        $this->assertTrue($form->isSynchronized());
        $this->assertEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertEquals('hearts', $data);
    }

    public function testLoadExistingMultipleData(): void
    {
        $this->options['multiple'] = true;
        $form = $this->factory->create(ChoiceWithOtherType::class, 'hearts,clubs', $this->options);
        $form->submit(['choices' => ['hearts', 'clubs']]);
        $this->assertTrue($form->isSynchronized());
        $this->assertEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertEquals('hearts,clubs', $data);
    }

    public function testLoadExistingOtherData(): void
    {
        $form = $this->factory->create(ChoiceWithOtherType::class, 'stars', $this->options);
        $form->submit(['choices' => ChoiceWithOtherType::OTHER_VALUE, 'other' => 'stars']);
        $this->assertTrue($form->isSynchronized());
        $this->assertEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertEquals('stars', $data);
    }

    public function testLoadExistingOtherMultipleData(): void
    {
        $this->options['multiple'] = true;
        $form = $this->factory->create(ChoiceWithOtherType::class, 'hearts,stars', $this->options);
        $form->submit(['choices' => ['hearts', ChoiceWithOtherType::OTHER_VALUE], 'other' => 'stars']);
        $this->assertTrue($form->isSynchronized());
        $this->assertEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertEquals('hearts,stars', $data);
    }
}
