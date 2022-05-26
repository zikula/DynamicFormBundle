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
use Symfony\Component\Form\Exception\TransformationFailedException;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\ChoiceWithOtherType;

/**
 * @implements DataTransformerInterface<mixed, mixed>
 */
class ChoiceWithOtherValueTransformer implements DataTransformerInterface
{
    private array $choices;
    private bool $multiple;

    public function __construct(array $options)
    {
        $this->choices = $options['choices'];
        $this->multiple = $options['multiple'];
    }

    /**
     * Stored value to form value.
     */
    public function transform($value): array
    {
        $defaultValue = $this->multiple ? [] : '';
        if (null === $value || '' === $value || [] === $value) {
            return ['choices' => $defaultValue, 'other' => ''];
        }

        return $this->createFormValue($value);
    }

    private function createFormValue($storedValue): array
    {
        if (is_array($storedValue)) {
            $intersection = array_intersect($this->choices, $storedValue);
            $others = array_diff($storedValue, $intersection);
            if (count($others) > 0) {
                $intersection['Other'] = ChoiceWithOtherType::OTHER_VALUE;
            }

            return ['choices' => array_values($intersection), 'other' => implode(',', $others)];
        }

        if (in_array($storedValue, $this->choices, true)) {
            return ['choices' => $storedValue, 'other' => ''];
        }

        return ['choices' => 'other', 'other' => $storedValue];
    }

    /**
     * Form value to stored value.
     */
    public function reverseTransform($value)
    {
        if (!isset($value['choices'], $value['other'])) {
            throw new TransformationFailedException('Array keys not properly set.');
        }

        if (!$this->multiple) {
            return $value['choices'] === ChoiceWithOtherType::OTHER_VALUE ? $value['other'] : $value['choices'];
        }

        $selectedValues = $value['choices'];
        if (in_array(ChoiceWithOtherType::OTHER_VALUE, $selectedValues, true)) {
            $otherKey = array_search(ChoiceWithOtherType::OTHER_VALUE, $selectedValues, true);
            unset($selectedValues[$otherKey]);
            $otherValue = false === strpos($value['other'], ',') ? [$value['other']] : explode(',', $value['other']);
            $selectedValues = array_merge($selectedValues, $otherValue);
        }

        return $selectedValues;
    }
}
