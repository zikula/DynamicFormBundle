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

namespace Zikula\Bundle\DynamicFormPropertyBundle\Event;

use Zikula\Bundle\DynamicFormPropertyBundle\Form\Data\FormTypesChoices;

class FormTypeChoiceEvent
{
    protected FormTypesChoices $choices;

    public function __construct(?FormTypesChoices $choices = null)
    {
        if (null === $choices) {
            $choices = new FormTypesChoices();
        }
        $this->setChoices($choices);
    }

    public function getChoices(): FormTypesChoices
    {
        return $this->choices;
    }

    public function setChoices(FormTypesChoices $choices): void
    {
        $this->choices = $choices;
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

    public function addChoices(array $choices): void
    {
        foreach ($choices as $choice) {
            if (!isset($choice['groupName'], $choice['label'], $choice['formType'])) {
                throw new \InvalidArgumentException();
            }
            $this->addChoice(...$choice);
        }
    }

    public function removeChoice(string $groupName, string $label): void
    {
        unset($this->choices[$groupName][$label]);
    }

    public function removeChoices(array $choices): void
    {
        foreach ($choices as $choice) {
            if (!isset($choice['groupName'], $choice['label'])) {
                throw new \InvalidArgumentException();
            }
            $this->removeChoice(...$choice);
        }
    }
}
