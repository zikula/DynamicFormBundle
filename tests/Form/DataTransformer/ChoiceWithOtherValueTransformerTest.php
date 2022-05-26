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

namespace Zikula\Bundle\DynamicFormPropertyBundle\Tests\Form\DataTransformer;

use PHPUnit\Framework\TestCase;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer\ChoiceWithOtherValueTransformer;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\ChoiceWithOtherType;

class ChoiceWithOtherValueTransformerTest extends TestCase
{
    private array $options = [
        'choices' => [
            'Spades' => 'spades',
            'Diamonds' => 'diamonds',
            'Hearts' => 'hearts',
            'Clubs' => 'clubs',
            'Other' => ChoiceWithOtherType::OTHER_VALUE,
            ],
        'multiple' => false,
    ];

    /**
     * @covers ChoiceWithOtherValueTransformer::transform
     * @dataProvider data
     */
    public function testTransform(?string $storedAs, array $formValues): void
    {
        $transformer = new ChoiceWithOtherValueTransformer($this->options);
        $this->assertSame($formValues, $transformer->transform($storedAs));
    }

    /**
     * @covers ChoiceWithOtherValueTransformer::reverseTransform
     * @dataProvider data
     */
    public function testReverseTransform(?string $storedAs, array $formValues): void
    {
        $transformer = new ChoiceWithOtherValueTransformer($this->options);
        $this->assertSame($storedAs ?? '', $transformer->reverseTransform($formValues));
    }

    public function data(): \Iterator
    {
        yield 0 => [null, ['choices' => '', 'other' => '']];
        yield 1 => ['', ['choices' => '', 'other' => '']];
        yield 2 => ['spades', ['choices' => 'spades', 'other' => '']];
        yield 3 => ['Hearts', ['choices' => 'other', 'other' => 'Hearts']]; // case-sensitive
        yield 4 => ['stars', ['choices' => 'other', 'other' => 'stars']]; // non-value
    }

    /**
     * @covers ChoiceWithOtherValueTransformer::transform
     * @dataProvider multipleData
     */
    public function testTransformWithMultiple(?array $storedAs, array $formValues): void
    {
        $options = $this->options;
        $options['multiple'] = true;
        $transformer = new ChoiceWithOtherValueTransformer($options);
        $this->assertSame($formValues, $transformer->transform($storedAs));
    }

    /**
     * @covers ChoiceWithOtherValueTransformer::reverseTransform
     * @dataProvider multipleData
     */
    public function testReverseTransformWithMultiple(?array $storedAs, array $formValues): void
    {
        $options = $this->options;
        $options['multiple'] = true;
        $transformer = new ChoiceWithOtherValueTransformer($options);
        $this->assertSame($storedAs ?? [], $transformer->reverseTransform($formValues));
    }

    public function multipleData(): \Iterator
    {
        yield 0 => [null, ['choices' => [], 'other' => '']];
        yield 1 => [[], ['choices' => [], 'other' => '']];
        yield 2 => [['spades', 'hearts'], ['choices' => ['spades', 'hearts'], 'other' => '']];
        yield 3 => [['spades', 'hearts', 'moons'], ['choices' => ['spades', 'hearts', 'other'], 'other' => 'moons']];
        yield 4 => [['hearts', 'Spades'], ['choices' => ['hearts', 'other'], 'other' => 'Spades']];
        yield 5 => [['stars', 'moons'], ['choices' => ['other'], 'other' => 'stars,moons']];
    }
}
