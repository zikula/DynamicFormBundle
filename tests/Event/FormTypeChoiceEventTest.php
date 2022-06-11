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

namespace Zikula\Bundle\DynamicFormBundle\Tests\Event;

use PHPUnit\Framework\TestCase;
use Zikula\Bundle\DynamicFormBundle\Event\FormTypeChoiceEvent;
use Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices;

/**
 * @covers \Zikula\Bundle\DynamicFormBundle\Event\FormTypeChoiceEvent
 */
class FormTypeChoiceEventTest extends TestCase
{
    public function testConstruction(): void
    {
        $choices = [
            'g1' => [
                'foo' => 'bar',
                'faz' => 'baz',
            ],
        ];
        $event = new FormTypeChoiceEvent(new FormTypesChoices($choices));
        $this->assertSame('bar', $event->getChoices()['g1']['foo']);
        $this->assertSame('baz', $event->getChoices()['g1']['faz']);
        $this->assertArrayNotHasKey('fee', $event->getChoices());
    }

    public function testSetChoices(): void
    {
        $event = new FormTypeChoiceEvent();
        $choices = [
            'g1' => [
                'foo' => 'bar',
                'faz' => 'baz',
            ],
        ];
        $event->setChoices(new FormTypesChoices($choices));
        $this->assertSame('bar', $event->getChoices()['g1']['foo']);
        $this->assertSame('baz', $event->getChoices()['g1']['faz']);
        $this->assertArrayNotHasKey('fee', $event->getChoices());
    }

    public function testAddChoice(): void
    {
        $event = new FormTypeChoiceEvent();
        $event->addChoice('g1', 'label1', 'FormType');
        $this->assertSame(['label1' => 'FormType'], $event->getChoices()['g1']);
    }

    public function testAddChoices(): void
    {
        $event = new FormTypeChoiceEvent();
        $choices = [
            [
                'groupName' => 'g1',
                'label' => 'label1',
                'formType' => 'formType',
            ],
            [
                'groupName' => 'g2',
                'label' => 'label2',
                'formType' => 'formType2',
            ],
        ];
        $event->addChoices($choices);
        $this->assertSame(['label1' => 'formType'], $event->getChoices()['g1']);
        $this->assertSame(['label2' => 'formType2'], $event->getChoices()['g2']);
    }

    public function testRemoveChoice(): void
    {
        $choices = [
            'g1' => [
                'foo' => 'bar',
                'faz' => 'baz',
            ],
        ];
        $event = new FormTypeChoiceEvent(new FormTypesChoices($choices));
        $this->assertSame('bar', $event->getChoices()['g1']['foo']);
        $event->removeChoice('g1', 'foo');
        $this->assertArrayNotHasKey('foo', $event->getChoices()['g1']);
    }

    public function testRemoveChoices(): void
    {
        $choices = [
            'g1' => [
                'foo' => 'bar',
                'faz' => 'baz',
                'fee' => 'bee',
                'fum' => 'bum',
            ],
        ];
        $event = new FormTypeChoiceEvent(new FormTypesChoices($choices));
        $this->assertSame('bar', $event->getChoices()['g1']['foo']);
        $this->assertSame('baz', $event->getChoices()['g1']['faz']);
        $this->assertSame('bee', $event->getChoices()['g1']['fee']);
        $this->assertSame('bum', $event->getChoices()['g1']['fum']);
        $removedChoices = [
            [
                'groupName' => 'g1',
                'label' => 'foo',
            ],
            [
                'groupName' => 'g1',
                'label' => 'faz',
            ],
        ];
        $event->removeChoices($removedChoices);
        $this->assertArrayNotHasKey('foo', $event->getChoices()['g1']);
        $this->assertArrayNotHasKey('faz', $event->getChoices()['g1']);
        $this->assertSame('bee', $event->getChoices()['g1']['fee']);
        $this->assertSame('bum', $event->getChoices()['g1']['fum']);
    }
}
