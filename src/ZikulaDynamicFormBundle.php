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

namespace Zikula\Bundle\DynamicFormBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ZikulaDynamicFormBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
