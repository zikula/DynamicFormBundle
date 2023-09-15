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

namespace Zikula\Bundle\DynamicFormBundle\Form\DataMapper;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormInterface;
use Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceWithOtherType;

class ChoiceWithOtherDataMapper implements DataMapperInterface
{
    public function mapDataToForms($viewData, \Traversable $forms): void
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);
        $formParent = $forms['choices']->getParent();
        if (!$formParent) {
            return;
        }
        $formChoices = $formParent->getConfig()->getOption('choices');
        $choices = $formParent->getConfig()->getOption('multiple') ? [] : '';
        $other = '';

        if (!empty($viewData)) {
            if (false !== strpos($viewData, ',')) {
                $viewData = explode(',', $viewData);
            }
            if (is_array($viewData)) {
                $intersection = array_intersect($formChoices, $viewData);
                $others = array_diff($viewData, $intersection);
                if (count($others) > 0) {
                    $intersection['Other'] = ChoiceWithOtherType::OTHER_VALUE;
                }
                $choices = array_values($intersection);
                $other = implode(',', $others);
            } elseif (in_array($viewData, $formChoices, true)) {
                $choices = $viewData;
            } else {
                $choices = ChoiceWithOtherType::OTHER_VALUE;
                $other = $viewData;
            }
        }

        $forms['choices']->setData($choices);
        $forms['other']->setData($other);
    }

    public function mapFormsToData(\Traversable $forms, &$viewData): void
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);
        $choicesData = $forms['choices']->getData();
        $otherData = $forms['other']->getData();
        $formParent = $forms['choices']->getParent();
        if (!$formParent) {
            return;
        }
        $formMultiple = $formParent->getConfig()->getOption('multiple');

        if (!$formMultiple) {
            $viewData = ChoiceWithOtherType::OTHER_VALUE === $choicesData ? $otherData : $choicesData;
        } else {
            if (in_array(ChoiceWithOtherType::OTHER_VALUE, $choicesData, true)) {
                $otherKey = array_search(ChoiceWithOtherType::OTHER_VALUE, $choicesData, true);
                unset($choicesData[$otherKey]);
                if ($otherData) {
                    $otherValue = false === strpos($otherData, ',') ? [$otherData] : explode(',', $otherData);
                } else {
                    $otherValue = [];
                }
                $choicesData = array_merge($choicesData, $otherValue);
            }

            $viewData = implode(',', $choicesData);
        }
    }
}
