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

namespace Zikula\Bundle\DynamicFormBundle\Provider;

use Symfony\Component\Intl\Locales;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Zikula\Bundle\DynamicFormBundle\Event\SupportedLocalesEvent;

class LocaleProvider implements LocaleProviderInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private bool $translate;

    public function __construct(EventDispatcherInterface $eventDispatcher, bool $translate = false)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translate = $translate;
    }

    public function getSupportedLocales(): array
    {
        if (!$this->translate) {
            return ['default'];
        }
        $this->eventDispatcher->dispatch($event = new SupportedLocalesEvent(['default']));

        return $event->getSupportedLocales();
    }

    public function getSupportedLocaleNames(?string $displayLocale = null): array
    {
        if (!$this->translate) {
            return ['Default' => 'default'];
        }
        $locales = $this->getSupportedLocales();
        $namedLocales = [];
        foreach ($locales as $locale) {
            $localeName = 'default' === $locale ? 'Default' : Locales::getName($locale, $displayLocale);
            $namedLocales[ucfirst($localeName)] = $locale;
        }
        ksort($namedLocales);

        return $namedLocales;
    }
}
