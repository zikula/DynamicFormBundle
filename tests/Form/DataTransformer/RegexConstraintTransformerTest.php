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
use Symfony\Component\Validator\Constraints\Regex;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer\RegexConstraintTransformer;

class RegexConstraintTransformerTest extends TestCase
{
    /**
     * @covers RegexConstraintTransformer::transform
     * @dataProvider data
     */
    public function testTransform($storedAs, string $submitted, string $restored): void
    {
        $transformer = new RegexConstraintTransformer();
        $this->assertSame($restored, $transformer->transform($storedAs));
    }

    /**
     * @covers RegexConstraintTransformer::reverseTransform
     * @dataProvider data
     */
    public function testReverseTransform($storedAs, string $submitted, string $restored): void
    {
        $transformer = new RegexConstraintTransformer();
        $this->assertEquals($storedAs, $transformer->reverseTransform($submitted));
    }

    public function data(): \Iterator
    {
        yield 0 => [[new Regex('/^\w+$/')], '/^\w+$/', '/^\w+$/'];
    }
}
