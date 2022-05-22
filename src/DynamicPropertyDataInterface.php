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

namespace Zikula\Bundle\DynamicFormPropertyBundle;

interface DynamicPropertyDataInterface
{
    /**
     * @return array<string, mixed>|null
     */
    public function getData(): ?array;

    /**
     * @param array<string, mixed>|null $data
     */
    public function setData(?array $data): void;
}
