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

/**
 * Represents a form object (“data_class”) containing dynamic fields.
 */
interface DynamicPropertiesContainerInterface
{
    /**
     * Returns a list of field specifications.
     *
     * @return DynamicPropertyInterface[]
     */
    public function getDynamicFieldsSpecification(array $params = []): array;
}