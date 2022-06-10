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
class DefaultLabelToLabelsTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value['default'] ?? '';
    }

    public function reverseTransform($value)
    {
        return ['default' => $value];
    }
}
