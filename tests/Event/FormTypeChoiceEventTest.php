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
use Zikula\Bundle\DynamicFormBundle\Event\SupportedLocalesEvent;
use Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices;

class FormTypeChoiceEventTest extends TestCase
{
    public function testConstruction(): void
    {
        $event = new FormTypeChoiceEvent();
        var_dump($event->getChoices());
        $this->assertEmpty($event->getChoices());

        $choices = [
            'foo' => 'bar',
            'faz' => 'baz',
        ];
        $event = new FormTypeChoiceEvent(new FormTypesChoices($choices));
        $this->assertSame($choices, $event->getChoices());
    }

    public function testSetChoices(): void
    {
        $event = new FormTypeChoiceEvent();
        $choices = [
            'foo' => 'bar',
            'faz' => 'baz',
        ];
        $event->setChoices(new FormTypesChoices($choices));
        $this->assertSame($choices, $event->getChoices());
    }

}
