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

interface LocaleProviderInterface
{
    /**
     * Return a list of supported locales by region.
     *
     * @return string[]
     */
    public function getSupportedLocales(bool $includeRegions = true): array;

    /**
     * Return an associative list of supported locales by region by their translated names.
     *
     * @return string[]
     */
    public function getSupportedLocaleNames(string $region = null, string $displayLocale = null, bool $includeRegions = true): array;
}