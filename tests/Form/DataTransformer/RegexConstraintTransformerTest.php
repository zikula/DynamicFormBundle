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
use Symfony\Component\Validator\Constraints\Regex;
use Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\RegexConstraintTransformer;

/**
 * @uses \Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\RegexConstraintTransformer
 */
class RegexConstraintTransformerTest extends TestCase
{
    /**
     * @param Regex[] $storedAs
     *
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\RegexConstraintTransformer::transform
     * @dataProvider data
     */
    public function testTransform(array $storedAs, string $submitted, string $restored): void
    {
        $transformer = new RegexConstraintTransformer();
        $this->assertSame($restored, $transformer->transform($storedAs));
    }

    /**
     * @param Regex[] $storedAs
     *
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\DataTransformer\RegexConstraintTransformer::reverseTransform
     * @dataProvider data
     */
    public function testReverseTransform(array $storedAs, string $submitted, string $restored): void
    {
        $transformer = new RegexConstraintTransformer();
        $this->assertEquals($storedAs, $transformer->reverseTransform($submitted));
    }

    public function data(): \Iterator
    {
        yield 0 => [[new Regex('/^\w+$/')], '/^\w+$/', '/^\w+$/'];
    }
}
