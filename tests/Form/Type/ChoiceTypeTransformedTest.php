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

use Symfony\Component\Form\Test\TypeTestCase;
use Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceTypeTransformed;

/**
 * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceTypeTransformed
 * @covers \Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\ArrayToStringTransformer
 */
class ChoiceTypeTransformedTest extends TypeTestCase
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

    public function testSubmitValidData(): void
    {
        $form = $this->factory->create(ChoiceTypeTransformed::class, [], $this->options);
        $form->submit('');
        $this->assertTrue($form->isSynchronized());
        $this->assertEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertEquals('', $data);

        $form = $this->factory->create(ChoiceTypeTransformed::class, [], $this->options);
        $form->submit('spades');
        $this->assertTrue($form->isSynchronized());
        $this->assertEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertEquals('spades', $data);
    }

    public function testSubmitInvalidData(): void
    {
        $form = $this->factory->create(ChoiceTypeTransformed::class, [], $this->options);
        $form->submit('stars');
        $this->assertFalse($form->isSynchronized());
        $this->assertNotEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertNull($data);
        $this->assertContains($form->getErrors()->current()->getMessage(), ['This value is not valid.', 'The selected choice is invalid.']); // php7 or php8
    }

    public function testSubmitMultipleValidData(): void
    {
        $this->options['multiple'] = true;
        $form = $this->factory->create(ChoiceTypeTransformed::class, [], $this->options);
        $form->submit(['spades', 'hearts']);
        $this->assertTrue($form->isSynchronized());
        $this->assertEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertEquals('spades,hearts', $data);
    }

    public function testSubmitExpandedValidData(): void
    {
        $this->options['expanded'] = true;
        $form = $this->factory->create(ChoiceTypeTransformed::class, [], $this->options);
        $form->submit('spades');
        $this->assertTrue($form->isSynchronized());
        $this->assertEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertEquals('spades', $data);
    }

    public function testSubmitMultipleExpandedValidData(): void
    {
        $this->options['multiple'] = true;
        $this->options['expanded'] = true;
        $form = $this->factory->create(ChoiceTypeTransformed::class, [], $this->options);
        $form->submit(['spades', 'hearts']);
        $this->assertTrue($form->isSynchronized());
        $this->assertEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertEquals('spades,hearts', $data);
    }
}
