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

namespace Zikula\Bundle\DynamicFormBundle\Container;

use Zikula\Bundle\DynamicFormBundle\FormSpecificationInterface;

interface SpecificationContainerInterface
{
    /**
     * Returns a list of form specifications.
     *
     * @param array<string, mixed> $params parameters used to filter the array
     *
     * @return FormSpecificationInterface[]
     */
    public function getFormSpecifications(array $params = []): array;

    /**
     * Return a list of form labels by name.
     *
     * @return array<string, string> [name => translatedLabel]
     */
    public function getLabels(string $locale = ''): array;

    /**
     * Return a list of form labels by group.
     *
     * @return array<string, array<string, string>> [group => [name => translatedLabel]]
     */
    public function getGroupedLabels(string $locale = ''): array;
}
