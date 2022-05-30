<?php

namespace Zikula\Bundle\DynamicFormPropertyBundle\Form\DataMapper;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormInterface;
use Zikula\Bundle\DynamicFormPropertyBundle\Form\Type\ChoiceWithOtherType;

class ChoiceWithOtherDataMapper implements DataMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public function mapDataToForms(mixed $value, \Traversable $forms): void
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

        if (!empty($value)) {
            if (false !== strpos($value, ',')) {
                $value = explode(',', $value);
            }
            if (is_array($value)) {
                $intersection = array_intersect($formChoices, $value);
                $others = array_diff($value, $intersection);
                if (count($others) > 0) {
                    $intersection['Other'] = ChoiceWithOtherType::OTHER_VALUE;
                }
                $choices = array_values($intersection);
                $other = implode(',', $others);
            } elseif (in_array($value, $formChoices, true)) {
                $choices = $value;
            } else {
                $choices = ChoiceWithOtherType::OTHER_VALUE;
                $other = $value;
            }
        }

        $forms['choices']->setData($choices);
        $forms['other']->setData($other);
    }

    /**
     * {@inheritDoc}
     */
    public function mapFormsToData(\Traversable $forms, mixed &$value): void
    {
        if (!\is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }
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
            $value = ChoiceWithOtherType::OTHER_VALUE === $choicesData ? $otherData : $choicesData;
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

            $value = is_array($choicesData) ? implode(',', $choicesData) : $choicesData;
        }
    }
}
