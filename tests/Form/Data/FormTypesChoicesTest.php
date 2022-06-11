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

namespace Zikula\Bundle\DynamicFormBundle\Tests\Form\Data;

use ArrayAccess;
use Iterator;
use PHPUnit\Framework\TestCase;
use Traversable;
use Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices;

/**
 * @uses \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices
 */
class FormTypesChoicesTest extends TestCase
{
    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices
     */
    public function testEmptyInstantiation(): void
    {
        $foo = new FormTypesChoices();
        $this->assertInstanceOf(ArrayAccess::class, $foo);
        $this->assertInstanceOf(Iterator::class, $foo);
        $this->assertInstanceOf(Traversable::class, $foo);
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices
     */
    public function testInstantiationWithArg(): void
    {
        $foo = new FormTypesChoices([
            'foo' => ['bar' => 'bar'],
            'three' => ['nine' => 'nine'],
        ]);
        $this->assertInstanceOf(ArrayAccess::class, $foo);
        $this->assertInstanceOf(Iterator::class, $foo);
        $this->assertInstanceOf(Traversable::class, $foo);
        $this->assertArrayHasKey('foo', $foo);
        $this->assertArrayHasKey('three', $foo);
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::offsetSet
     */
    public function testAdd(): void
    {
        $foo = new FormTypesChoices([
            'foo' => ['bar' => 'bar'],
            'three' => ['nine' => 'nine'],
        ]);
        $this->assertArrayNotHasKey('six', $foo);
        $foo['six'] = ['sixvalue' => 'sixvalue'];
        $this->assertArrayHasKey('six', $foo);
        $this->assertEquals(['sixvalue' => 'sixvalue'], $foo['six']);
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::offsetUnset
     */
    public function testExceptionOnUnset(): void
    {
        $this->expectException(\Exception::class);
        $foo = new FormTypesChoices([
            'foo' => ['bar' => 'bar'],
            'three' => ['nine' => 'nine'],
        ]);
        unset($foo['foo']);
    }
}
