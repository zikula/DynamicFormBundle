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

namespace Zikula\Bundle\DynamicFormBundle\Event;

use Zikula\Bundle\DynamicFormBundle\Form\Data\FormTypesChoices;

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
        $this->choices->addChoice($groupName, $label, $formType);
    }

    /**
     * @param array{mixed, array{groupName:string, label:string, formType:string}} $choices
     */
    public function addChoices(array $choices): void
    {
        $this->choices->addChoices($choices);
    }

    public function removeChoice(string $groupName, string $label): void
    {
        $this->choices->removeChoice($groupName, $label);
    }

    /**
     * @param array{mixed, array{groupName:string, label:string}} $choices
     */
    public function removeChoices(array $choices): void
    {
        $this->choices->removeChoices($choices);
    }
}
