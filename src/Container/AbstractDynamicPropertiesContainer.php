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

abstract class AbstractDynamicPropertiesContainer implements DynamicPropertiesContainerInterface
{
    /**
     * {@inheritDoc}
     */
    abstract public function getPropertySpecifications(array $params = []): array;

    /**
     * {@inheritDoc}
     */
    public function getLabels(string $locale = ''): array
    {
        $labels = [];
        foreach ($this->getPropertySpecifications() as $specification) {
            $labels[$specification->getName()] = $specification->getLabel($locale);
        }

        return $labels;
    }

    /**
     * {@inheritDoc}
     */
    public function getGroupedLabels(string $locale = ''): array
    {
        $labels = [];
        foreach ($this->getPropertySpecifications() as $specification) {
            $labels[$specification->getGroup($locale)][$specification->getName()] = $specification->getLabel($locale);
        }

        return $labels;
    }
}
