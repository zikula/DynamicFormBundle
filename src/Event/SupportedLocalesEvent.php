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

namespace Zikula\Bundle\DynamicFormBundle\Event;

class SupportedLocalesEvent
{
    /**
     * @var string[]
     */
    private array $supportedLocales;

    /**
     * @param string[] $supportedLocales
     */
    public function __construct(array $supportedLocales = [])
    {
        $this->supportedLocales = $supportedLocales;
    }

    /**
     * @return string[]
     */
    public function getSupportedLocales(): array
    {
        return $this->supportedLocales;
    }

    /**
     * @param string[] $supportedLocales
     */
    public function setSupportedLocales(array $supportedLocales): void
    {
        $this->supportedLocales = $supportedLocales;
    }
}
