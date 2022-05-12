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

use ArrayAccess;
use Exception;
use Iterator;

/**
 * Object to manage the available choices when selecting a FormType in DynamicFieldType.
 */
class FormTypesChoices implements ArrayAccess, Iterator
{
    private array $choices;

    public function __construct(array $choices = [])
    {
        $this->choices = $choices;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->choices[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->choices[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->choices[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        throw new Exception('Not allowed to unset!');
    }

    public function rewind(): void
    {
        reset($this->choices);
    }

    public function current(): mixed
    {
        return current($this->choices);
    }

    public function key(): mixed
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
}
