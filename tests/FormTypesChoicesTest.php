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

namespace Zikula\Bundle\DynamicFormPropertyBundle\Tests;

use ArrayAccess;
use Iterator;
use PHPUnit\Framework\TestCase;
use Traversable;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Data\FormTypesChoices;

class FormTypesChoicesTest extends TestCase
{
    public function testEmptyInstantiation(): void
    {
        $foo = new FormTypesChoices();
        $this->assertInstanceOf(ArrayAccess::class, $foo);
        $this->assertInstanceOf(Iterator::class, $foo);
        $this->assertInstanceOf(Traversable::class, $foo);
    }

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
