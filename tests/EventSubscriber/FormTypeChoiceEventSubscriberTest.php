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

namespace Zikula\Bundle\DynamicFormBundle\Tests\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Translation\IdentityTranslator;
use Zikula\Bundle\DynamicFormBundle\Event\FormTypeChoiceEvent;
use Zikula\Bundle\DynamicFormBundle\EventSubscriber\FormTypeChoiceEventSubscriber;

/**
 * @covers \Zikula\Bundle\DynamicFormBundle\EventSubscriber\FormTypeChoiceEventSubscriber
 */
class FormTypeChoiceEventSubscriberTest extends TestCase
{
    private EventDispatcher $dispatcher;

    protected function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
        $translator = new IdentityTranslator();
        $this->dispatcher->addSubscriber(new FormTypeChoiceEventSubscriber($translator));
    }

    public function testSubscriberIsCalled(): void
    {
        $event = new FormTypeChoiceEvent();
        $this->dispatcher->dispatch($event);
        $choices = $event->getChoices();
        $this->assertArrayHasKey('Text fields', $choices);
        $this->assertArrayHasKey('Text', $choices['Text fields']);
        $this->assertArrayHasKey('Choice fields', $choices);
        $this->assertArrayHasKey('Country', $choices['Choice fields']);
        $this->assertArrayHasKey('Date and time fields', $choices);
        $this->assertArrayHasKey('Birthday', $choices['Date and time fields']);
        $this->assertArrayHasKey('Other fields', $choices);
        $this->assertArrayHasKey('File', $choices['Other fields']);
    }
}
