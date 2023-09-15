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

use PHPUnit\Framework\TestCase;
use Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices;

class FormTypesChoicesTest extends TestCase
{
    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices
     */
    public function testEmptyInstantiation(): void
    {
        $foo = new FormTypesChoices();
        $this->assertInstanceOf(\ArrayAccess::class, $foo);
        $this->assertInstanceOf(\Iterator::class, $foo);
        $this->assertInstanceOf(\Traversable::class, $foo);
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
        $this->assertInstanceOf(\ArrayAccess::class, $foo);
        $this->assertInstanceOf(\Iterator::class, $foo);
        $this->assertInstanceOf(\Traversable::class, $foo);
        $this->assertArrayHasKey('foo', $foo);
        $this->assertArrayHasKey('three', $foo);
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::offsetSet
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::offsetGet
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

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::addChoice
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::offsetExists
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::offsetGet
     */
    public function testAddChoice(): void
    {
        $foo = new FormTypesChoices([
            'foo' => ['bar' => 'bar'],
            'three' => ['nine' => 'nine'],
        ]);
        $foo->addChoice('boo', 'one', 'oneFormType');
        $this->assertTrue($foo->offsetExists('boo'));
        $this->assertEquals('oneFormType', $foo['boo']['one']);
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::removeChoice
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::offsetExists
     */
    public function testRemoveChoice(): void
    {
        $foo = new FormTypesChoices([
            'foo' => ['bar' => 'bar'],
            'three' => ['nine' => 'nine'],
        ]);
        $foo->removeChoice('foo', 'bar');
        $this->assertFalse($foo->offsetExists('boo'));
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::addChoices
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::offsetExists
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::offsetGet
     */
    public function testAddChoices(): void
    {
        $foo = new FormTypesChoices([
            'foo' => ['bar' => 'bar'],
            'three' => ['nine' => 'nine'],
        ]);
        $foo->addChoices([
            [
                'groupName' => 'boo',
                'label' => 'one',
                'formType' => 'oneFormType',
            ],
            [
                'groupName' => 'boo',
                'label' => 'ten',
                'formType' => 'tenFormType',
            ],
        ]);
        $this->assertTrue($foo->offsetExists('boo'));
        $this->assertEquals('oneFormType', $foo['boo']['one']);
        $this->assertEquals('tenFormType', $foo['boo']['ten']);
    }

    public function testAddInvalidChoices(): void
    {
        $foo = new FormTypesChoices([
            'foo' => ['bar' => 'bar'],
            'three' => ['nine' => 'nine'],
        ]);
        $this->expectException(\InvalidArgumentException::class);
        $foo->addChoices([
            [
                'groupName' => 'boo',
                'label' => 'one',
                // 'formType' => 'oneFormType', missing!
            ],
            [
                'groupName' => 'boo',
                'label' => 'ten',
                'formType' => 'tenFormType',
            ],
        ]);
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::removeChoices
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::offsetExists
     */
    public function testRemoveChoices(): void
    {
        $foo = new FormTypesChoices([
            'foo' => ['bar' => 'bar', 'boo' => 'one', 'baz' => 'two'],
            'three' => ['nine' => 'v_nine', 'three' => 'v_three'],
            'four' => ['ten' => 'v_ten'],
            'five' => ['eleven' => 'v_eleven'],
            'six' => ['twelve' => 'v_twelve'],
        ]);
        $this->assertTrue($foo->offsetExists('three'));
        $this->assertEquals('v_nine', $foo['three']['nine']);
        $this->assertEquals('v_three', $foo['three']['three']);
        $this->assertTrue($foo->offsetExists('six'));
        $foo->removeChoices([
            [
                'groupName' => 'three',
                'label' => 'nine',
            ],
            [
                'groupName' => 'six',
                'label' => 'twelve',
            ],
        ]);
        $this->assertTrue($foo->offsetExists('three'));
        $this->assertArrayNotHasKey('nine', $foo['three']);
        $this->assertEquals('v_three', $foo['three']['three']);
        $this->assertFalse($foo->offsetExists('six'));
    }

    public function testRemoveInvalidChoice(): void
    {
        $foo = new FormTypesChoices([
            'foo' => ['bar' => 'bar', 'boo' => 'one', 'baz' => 'two'],
            'three' => ['nine' => 'v_nine', 'three' => 'v_three'],
            'four' => ['ten' => 'v_ten'],
            'five' => ['eleven' => 'v_eleven'],
            'six' => ['twelve' => 'v_twelve'],
        ]);
        $this->expectException(\InvalidArgumentException::class);
        $foo->removeChoices([
            [
                'groupName' => 'three',
                // 'label' => 'nine', missing!
            ],
            [
                'groupName' => 'six',
                'label' => 'twelve',
            ],
        ]);
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::rewind
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::valid
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::key
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::current
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices::next
     */
    public function testIterator(): void
    {
        $foo = new FormTypesChoices([
            'key1' => ['a' => '1val'],
            'key2' => ['a' => '2val'],
            'key3' => ['a' => '3val'],
            'key4' => ['a' => '4val'],
        ]);
        $this->assertIsIterable($foo);
        $i = 1;
        $foo->rewind();
        while ($foo->valid()) {
            $this->assertEquals('key'.$i, $foo->key());
            $this->assertEquals(['a' => $i.'val'], $foo->current());
            $foo->next();
            ++$i;
        }
    }
}
