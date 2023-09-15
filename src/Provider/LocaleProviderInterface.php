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

interface LocaleProviderInterface
{
    /**
     * Return a list of supported locales.
     *
     * @return string[]
     */
    public function getSupportedLocales(): array;

    /**
     * Return an associative list of supported locales by their translated names.
     *
     * @return array<string, string>
     */
    public function getSupportedLocaleNames(?string $displayLocale = null): array;
}
