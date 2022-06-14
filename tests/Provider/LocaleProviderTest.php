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

namespace Zikula\Bundle\DynamicFormBundle\Tests\Provider;

use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\Bundle\DynamicFormBundle\Event\SupportedLocalesEvent;
use Zikula\Bundle\DynamicFormBundle\Provider\LocaleProvider;

/**
 * @covers \Zikula\Bundle\DynamicFormBundle\Provider\LocaleProvider
 */
class LocaleProviderTest extends TestCase
{
    public function testGetSupportedLocalesUntranslated(): void
    {
        $dispatcher = new EventDispatcher();
        $provider = new LocaleProvider($dispatcher);
        $this->assertSame(['default'], $provider->getSupportedLocales());
    }

    public function testGetSupportedLocaleNamesUntranslated(): void
    {
        $dispatcher = new EventDispatcher();
        $provider = new LocaleProvider($dispatcher);
        $this->assertSame(['Default' => 'default'], $provider->getSupportedLocaleNames());
    }

    public function testGetSupportedLocalesTranslated(): void
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new class() implements EventSubscriberInterface {
            public static function getSubscribedEvents(): array
            {
                return [
                    SupportedLocalesEvent::class => 'addSupportedLocales',
                ];
            }

            public function addSupportedLocales(SupportedLocalesEvent $event): void
            {
                $supportedLocales = $event->getSupportedLocales();
                $supportedLocales = array_merge($supportedLocales, ['de', 'es', 'fr_FR', 'fr_BE']);
                $event->setSupportedLocales($supportedLocales);
            }
        });
        $provider = new LocaleProvider($dispatcher, true);
        $this->assertSame(['default', 'de', 'es', 'fr_FR', 'fr_BE'], $provider->getSupportedLocales());
    }

    public function testGetSupportedLocaleNamesTranslated(): void
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new class() implements EventSubscriberInterface {
            public static function getSubscribedEvents(): array
            {
                return [
                    SupportedLocalesEvent::class => 'addSupportedLocales',
                ];
            }

            public function addSupportedLocales(SupportedLocalesEvent $event): void
            {
                $supportedLocales = $event->getSupportedLocales();
                $supportedLocales = array_merge($supportedLocales, ['de', 'es', 'fr_FR', 'fr_BE']);
                $event->setSupportedLocales($supportedLocales);
            }
        });
        $provider = new LocaleProvider($dispatcher, true);
        $expected = [
            'Default' => 'default',
            'German' => 'de',
            'Spanish' => 'es',
            'French (France)' => 'fr_FR',
            'French (Belgium)' => 'fr_BE',
        ];
        $this->assertEquals($expected, $provider->getSupportedLocaleNames());
        $german = [
            'Default' => 'default',
            'Deutsch' => 'de',
            'Französisch (Belgien)' => 'fr_BE',
            'Französisch (Frankreich)' => 'fr_FR',
            'Spanisch' => 'es',
        ];
        $this->assertEquals($german, $provider->getSupportedLocaleNames('de'));
    }
}
