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
class ChoiceValuesTransformer implements DataTransformerInterface
{
    /**
     * Transforms choices array into a string.
     */
    public function transform($value): string
    {
        $strings = [];
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $strings[] = $k === $v ? $v : $v.':'.$k;
            }
        }

        return implode(', ', $strings);
    }

    /**
     * Transforms the string back into a choices array .
     *
     * @return array<mixed, string>
     */
    public function reverseTransform($value): array
    {
        if (empty($value)) {
            return [];
        }
        if (is_array($value)) {
            return $value;
        }
        $array = explode(',', $value);
        $newArray = [];
        foreach ($array as $v) {
            if (\mb_strpos($v, ':')) {
                list($k, $v) = explode(':', $v);
            } else {
                $k = $v;
            }
            $newArray[trim($v)] = trim($k);
        }

        return $newArray;
    }
}
