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

namespace Zikula\Bundle\DynamicFormPropertyBundle\Event;

class SupportedLocalesEvent
{
    private array $supportedLocales;

    public function __construct(array $supportedLocales = [])
    {
        $this->supportedLocales = $supportedLocales;
    }

    public function getSupportedLocales(): array
    {
        return $this->supportedLocales;
    }

    public function setSupportedLocales(array $supportedLocales): void
    {
        $this->supportedLocales = $supportedLocales;
    }
}