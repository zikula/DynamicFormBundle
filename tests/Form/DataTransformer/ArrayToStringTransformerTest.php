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

namespace Zikula\Bundle\DynamicFormBundle\Tests\Form\DataTransformer;

use PHPUnit\Framework\TestCase;
use Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\ArrayToStringTransformer;

/**
 * @covers \Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\ArrayToStringTransformer::__construct()
 */
class ArrayToStringTransformerTest extends TestCase
{
    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\ArrayToStringTransformer::transform
     *
     * @dataProvider data
     */
    public function testTransform(string $storedAs, string $submitted, string $restored): void
    {
        $transformer = new ArrayToStringTransformer(false);
        $this->assertSame($restored, $transformer->transform($storedAs));
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\ArrayToStringTransformer::reverseTransform
     *
     * @dataProvider data
     */
    public function testReverseTransform(string $storedAs, string $submitted, string $restored): void
    {
        $transformer = new ArrayToStringTransformer(false);
        $this->assertSame($storedAs, $transformer->reverseTransform($submitted));
    }

    public function data(): \Iterator
    {
        yield 0 => ['', '', ''];
        yield 1 => ['value1', 'value1', 'value1'];
    }

    /**
     * @param string[] $submitted
     * @param string[] $restored
     *
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\ArrayToStringTransformer::transform
     *
     * @dataProvider dataMultiple
     */
    public function testTransformMultiple(string $storedAs, array $submitted, array $restored): void
    {
        $transformer = new ArrayToStringTransformer(true);
        $this->assertSame($restored, $transformer->transform($storedAs));
    }

    /**
     * @param string[] $submitted
     * @param string[] $restored
     *
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\ArrayToStringTransformer::reverseTransform
     *
     * @dataProvider dataMultiple
     */
    public function testReverseTransformMultiple(string $storedAs, array $submitted, array $restored): void
    {
        $transformer = new ArrayToStringTransformer(true);
        $this->assertSame($storedAs, $transformer->reverseTransform($submitted));
    }

    public function dataMultiple(): \Iterator
    {
        yield 0 => ['', [], []];
        yield 1 => ['value1', ['value1'], ['value1']];
        yield 2 => ['value1,value2', ['value1', 'value2'], ['value1', 'value2']];
    }
}
