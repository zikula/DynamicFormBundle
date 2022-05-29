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
use Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer\DefaultLabelToLabelsTransformer;

class DefaultLabelToLabelsTransformerTest extends TestCase
{
    /**
     * @covers \Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer\DefaultLabelToLabelsTransformer::transform
     * @dataProvider data
     */
    public function testTransform(array $storedAs, string $submitted, string $restored): void
    {
        $transformer = new DefaultLabelToLabelsTransformer();
        $this->assertSame($restored, $transformer->transform($storedAs));
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer\DefaultLabelToLabelsTransformer::reverseTransform
     * @dataProvider data
     */
    public function testReverseTransform(array $storedAs, string $submitted, string $restored): void
    {
        $transformer = new DefaultLabelToLabelsTransformer();
        $this->assertSame($storedAs, $transformer->reverseTransform($submitted));
    }

    public function data(): \Iterator
    {
        yield 0 => [['default' => 'label'], 'label', 'label'];
        yield 1 => [['default' => ''], '', ''];
    }
}
