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

namespace Zikula\Bundle\DynamicFormPropertyBundle;

/**
 * Represents a single dynamic property specification.
 */
interface DynamicPropertyInterface
{
    /**
     * Returns name of form field.
     */
    public function getName(): string;

    /**
     * Returns optional prefix of form field.
     */
    public function getPrefix(): string;

    /**
     * Returns a list of labels per locale. e.g. ['en' => 'my label', 'de' => 'mein Etikett']
     * @return string[]
     */
    public function getLabels(): array;

    /**
     * Returns label for a specific locale.
     */
    public function getLabel(string $locale = '', string $default = 'en'): string;

    /**
     * Returns the FqCN of the form class (e.g. return IntegerType::class;)
     */
    public function getFormType(): string;

    /**
     * Returns an array of form options.
     */
    public function getFormOptions(): array;

    /**
     * Returns a weighting number for sorting properties.
     */
    public function getWeight(): int;

    /**
     * Returns boolean indicating if this property is active.
     */
    public function isActive(): bool;

    /**
     * Returns a list of group names per locale.
     * May optionally be used for dividing fields into several fieldsets.
     * @return string[]
     */
    public function getGroupNames(): array;
}
