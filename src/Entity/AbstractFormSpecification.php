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

namespace Zikula\Bundle\DynamicFormBundle\Entity;

use Zikula\Bundle\DynamicFormBundle\FormSpecificationInterface;

abstract class AbstractFormSpecification implements FormSpecificationInterface
{
    protected string $name;

    /**
     * @var array<string, string>
     */
    protected array $labels = [];

    protected string $formType = '';

    /**
     * @var array<string, mixed>
     */
    protected array $formOptions = [];

    protected bool $active = true;

    /**
     * @var array<string, string>
     */
    protected array $groups = [];

    /**
     * @return array<string, string>
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getLabel(string $locale = ''): string
    {
        if (!empty($locale) && isset($this->labels[$locale])) {
            return $this->labels[$locale];
        }
        if (isset($this->labels['default'])) {
            return $this->labels['default'];
        }
        $values = array_values($this->labels);
        $name = $this->name ?? 'unknown';

        return !empty($values[0]) ? array_shift($values) : ucfirst($name);
    }

    /**
     * @param array<string, string> $labels
     */
    public function setLabels(array $labels): void
    {
        $this->labels = $labels;
    }

    public function getFormType(): string
    {
        return $this->formType;
    }

    public function setFormType(string $formType): void
    {
        $this->formType = $formType;
    }

    /**
     * @return array<string, mixed>
     */
    public function getFormOptions(): array
    {
        if (!isset($this->formOptions['required'])) {
            $this->formOptions['required'] = false;
        }

        return $this->formOptions;
    }

    /**
     * @param array<string, mixed> $formOptions
     */
    public function setFormOptions(array $formOptions): void
    {
        $this->formOptions = $formOptions;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * @return array<string, string>
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getGroup(string $locale = ''): string
    {
        if (!empty($locale) && isset($this->groups[$locale])) {
            return $this->groups[$locale];
        }

        return $this->groups['default'] ?? 'Default';
    }

    /**
     * @param array<string, string> $groups
     */
    public function setGroups(array $groups): void
    {
        $this->groups = $groups;
    }
}
