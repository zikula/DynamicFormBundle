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

namespace Zikula\Bundle\DynamicFormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements DataTransformerInterface<mixed, mixed>
 */
class ArrayToStringTransformer implements DataTransformerInterface
{
    private bool $multiple = false;

    public function __construct(bool $multiple)
    {
        $this->multiple = $multiple;
    }

    public function transform($value)
    {
        if (!$value) {
            return $this->multiple ? [] : '';
        }

        return !$this->multiple ? $value : explode(',', $value);
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return '';
        }
        $value = is_array($value) ? $value : [$value];

        return implode(',', $value);
    }
}
