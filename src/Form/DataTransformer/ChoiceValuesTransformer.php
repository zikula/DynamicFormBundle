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

namespace Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use function Zikula\Bundle\DynamicFormPropertyBundle\Form\DataTransformer\mb_strpos;

/**
 * Class ChoiceValuesTransformer
 */
class ChoiceValuesTransformer implements DataTransformerInterface
{
    /**
     * Transforms choices array into a string.
     *
     * @param array $value
     */
    public function transform($value): string
    {
        $strings = [];
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $strings[] = $k === $v ? $v : $k . ':' . $v;
            }
        }

        return implode(', ', $strings);
    }

    /**
     * Transforms the string back into a choices array .
     *
     * @param string $value
     */
    public function reverseTransform($value): array
    {
        if (null === $value) {
            return [];
        }
        if (is_array($value)) {
            return $value;
        }
        $array = explode(',', $value);
        $newArray = [];
        foreach ($array as $v) {
            if (mb_strpos($v, ':')) {
                list($k, $v) = explode(':', $v);
            } else {
                $k = $v;
            }
            $newArray[trim($v)] = trim($k);
        }

        return $newArray;
    }
}