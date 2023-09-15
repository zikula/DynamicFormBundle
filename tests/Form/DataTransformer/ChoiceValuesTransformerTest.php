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
use Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\ChoiceValuesTransformer;

class ChoiceValuesTransformerTest extends TestCase
{
    /**
     * @param array<string, string> $storedAs
     *
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\ChoiceValuesTransformer::transform
     *
     * @dataProvider data
     */
    public function testTransform(array $storedAs, string $submitted, string $restored): void
    {
        $transformer = new ChoiceValuesTransformer();
        $this->assertSame($restored, $transformer->transform($storedAs));
    }

    /**
     * @param array<string, string> $storedAs
     *
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\ChoiceValuesTransformer::reverseTransform
     *
     * @dataProvider data
     */
    public function testReverseTransform(array $storedAs, string $submitted, string $restored): void
    {
        $transformer = new ChoiceValuesTransformer();
        $this->assertSame($storedAs, $transformer->reverseTransform($submitted));
    }

    public function data(): \Iterator
    {
        yield 0 => [[], '', ''];
        yield 1 => [['value1' => 'value1'], 'value1', 'value1'];
        yield 2 => [['value1' => 'value1', 'value2' => 'value2', 'value3' => 'value3'], 'value1,value2,value3', 'value1, value2, value3'];
        yield 3 => [['value1' => 'value1', 'value2' => 'value2', 'value3' => 'value3'], 'value1, value2, value3', 'value1, value2, value3'];
        yield 4 => [['value1' => 'value1', 'value2' => 'value2', 'value3' => 'value3'], '     value1,            value2,value3', 'value1, value2, value3'];
        yield 5 => [['value1' => '1'], '1:value1', '1:value1'];
        yield 6 => [['Label1' => 'value1', 'Label2' => 'value2', 'Label3' => 'value3'], 'value1:Label1, value2:Label2, value3:Label3', 'value1:Label1, value2:Label2, value3:Label3'];
        yield 7 => [['Label1' => '10', 'Label2' => '11', 'Label3' => '15'], '10:Label1, 11:Label2, 15:Label3', '10:Label1, 11:Label2, 15:Label3'];
        yield 8 => [['value1' => 'value1', 'value2' => 'value2', 'value3' => 'value3'], 'value1, value2, value3, value2', 'value1, value2, value3'];
    }
}
