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

abstract class AbstractSpecificationContainer implements SpecificationContainerInterface
{
    abstract public function getFormSpecifications(array $params = []): array;

    public function getLabels(string $locale = ''): array
    {
        $labels = [];
        foreach ($this->getFormSpecifications() as $specification) {
            $labels[$specification->getName()] = $specification->getLabel($locale);
        }

        return $labels;
    }

    public function getGroupedLabels(string $locale = ''): array
    {
        $labels = [];
        foreach ($this->getFormSpecifications() as $specification) {
            $labels[$specification->getGroup($locale)][$specification->getName()] = $specification->getLabel($locale);
        }

        return $labels;
    }
}
