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

namespace Zikula\Bundle\DynamicFormBundle\Form\Data;

use ArrayAccess;
use Iterator;

/**
 * Object to manage the available choices when selecting a FormType in FormSpecificationType.
 *
 * @implements Iterator<string|null, array<string, string>|false>
 * @implements ArrayAccess<string, array<string, string>>
 */
class FormTypesChoices implements \ArrayAccess, \Iterator
{
    /**
     * @var array<string, array<string, string>>
     */
    private array $choices;

    /**
     * @param array<string, array<string, string>> $choices
     */
    public function __construct(array $choices = [])
    {
        $this->choices = $choices;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->choices[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->choices[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->choices[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        throw new \Exception('Not allowed to unset!');
    }

    public function rewind(): void
    {
        reset($this->choices);
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        return current($this->choices);
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return key($this->choices);
    }

    public function next(): void
    {
        next($this->choices);
    }

    public function valid(): bool
    {
        return null !== key($this->choices);
    }

    public function addChoice(string $groupName, string $label, string $formType): void
    {
        if (!isset($this->choices[$groupName])) {
            $this->choices[$groupName] = [];
        }
        if (!isset($this->choices[$groupName][$label])) {
            $this->choices[$groupName][$label] = $formType;
        }
    }

    /**
     * @param array{mixed, array{groupName:string, label:string, formType:string}} $choices
     */
    public function addChoices(array $choices): void
    {
        foreach ($choices as $choice) {
            if (!isset($choice['groupName'], $choice['label'], $choice['formType'])) {
                throw new \InvalidArgumentException();
            }
            $this->addChoice(...array_values($choice));
        }
    }

    public function removeChoice(string $groupName, string $label): void
    {
        unset($this->choices[$groupName][$label]);
        if (empty($this->choices[$groupName])) {
            unset($this->choices[$groupName]);
        }
    }

    /**
     * @param array{mixed, array{groupName:string, label:string}} $choices
     */
    public function removeChoices(array $choices): void
    {
        foreach ($choices as $choice) {
            if (!isset($choice['groupName'], $choice['label'])) {
                throw new \InvalidArgumentException();
            }
            $this->removeChoice(...array_values($choice));
        }
    }
}
