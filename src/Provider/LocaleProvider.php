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

namespace Zikula\Bundle\DynamicFormPropertyBundle\Provider;

use Symfony\Component\Intl\Locales;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Zikula\Bundle\DynamicFormPropertyBundle\Event\SupportedLocalesEvent;

class LocaleProvider implements LocaleProviderInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private bool $translateLabels;

    public function __construct(EventDispatcherInterface $eventDispatcher, bool $translateLabels = false)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translateLabels = $translateLabels;
    }

    /**
     * @todo implement regions
     */
    public function getSupportedLocales(bool $includeRegions = true): array
    {
        if (!$this->translateLabels) {
            return ['default'];
        }
        $this->eventDispatcher->dispatch($event = new SupportedLocalesEvent(['default']));

        return $event->getSupportedLocales();
    }

    /**
     * @todo implement regions
     */
    public function getSupportedLocaleNames(string $region = null, string $displayLocale = null, bool $includeRegions = true): array
    {
        if (!$this->translateLabels) {
            return ['Default' => 'default'];
        }
        $locales = $this->getSupportedLocales($includeRegions);
        $namedLocales = [];
        foreach ($locales as $locale) {
            $localeName = 'default' === $locale ? 'Default' : Locales::getName($locale, $displayLocale);
            $namedLocales[ucfirst($localeName)] = $locale;
        }
        ksort($namedLocales);

        return $namedLocales;
    }
}
