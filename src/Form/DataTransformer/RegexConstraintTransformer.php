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
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @implements DataTransformerInterface<mixed, mixed>
 */
class RegexConstraintTransformer implements DataTransformerInterface
{
    /**
     * Transforms constraint into the text pattern.
     */
    public function transform($value): string
    {
        /** @var Regex $constraint */
        $constraint = is_array($value) && isset($value[0]) ? $value[0] : new Regex('/.*/');

        return $constraint->pattern;
    }

    /**
     * Transforms a regex pattern into an array of constraints.
     *
     * @return array<Regex>
     */
    public function reverseTransform($value): array
    {
        if (is_array($value)) {
            $value = $value[0];
        }
        if (!$value) {
            $value = '/.*/';
        } elseif ($value instanceof Regex) {
            $value = $value->pattern;
        }

        return [new Regex($value)];
    }
}
