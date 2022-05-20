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

interface DynamicPropertiesContainerInterface
{
    /**
     * Returns a list of property specifications.
     * @param array $params Parameters used to filter the array.
     *
     * @return DynamicPropertySpecificationInterface[]
     */
    public function getPropertySpecifications(array $params = []): array;
}
