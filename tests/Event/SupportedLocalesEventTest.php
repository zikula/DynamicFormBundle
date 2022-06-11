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
use Zikula\Bundle\DynamicFormBundle\Event\SupportedLocalesEvent;

/**
 * @covers \Zikula\Bundle\DynamicFormBundle\Event\SupportedLocalesEvent
 */
class SupportedLocalesEventTest extends TestCase
{
    public function testSetSupportedLocales(): void
    {
        $event = new SupportedLocalesEvent();
        $event->setSupportedLocales(['en', 'de', 'es']);
        $this->assertSame(['en', 'de', 'es'], $event->getSupportedLocales());
    }

    public function testGetSupportedLocales(): void
    {
        $event = new SupportedLocalesEvent(['en', 'de', 'es']);
        $this->assertSame(['en', 'de', 'es'], $event->getSupportedLocales());
    }

    public function testGetEmptySupportedLocales(): void
    {
        $event = new SupportedLocalesEvent();
        $this->assertEmpty($event->getSupportedLocales());
    }
}
