<?php

namespace Zikula\Bundle\DynamicFormPropertyBundle\Provider;

use Symfony\Component\Intl\Locales;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class LocaleProvider
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getSupportedLocales(bool $includeRegions = true, bool $syncConfig = true): array
    {
        return ['en', 'de', 'es'];
    }

    public function getSupportedLocaleNames(string $region = null, string $displayLocale = null, bool $includeRegions = true): array
    {
        $locales = $this->getSupportedLocales($includeRegions, false);
        $namedLocales = [];
        foreach ($locales as $locale) {
            $localeName = Locales::getName($locale, $displayLocale);
            $namedLocales[ucfirst($localeName)] = $locale;
        }
        ksort($namedLocales);

        return $namedLocales;
    }
}