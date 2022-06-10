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

namespace Zikula\Bundle\DynamicFormBundle;

/**
 * Represents a single form specification.
 */
interface FormSpecificationInterface
{
    /**
     * Returns name of form field.
     */
    public function getName(): string;

    /**
     * Returns a list of labels per locale. e.g. ['en' => 'my label', 'de' => 'mein Etikett'].
     *
     * @return string[]
     */
    public function getLabels(): array;

    /**
     * Returns label for a specific locale.
     */
    public function getLabel(string $locale = ''): string;

    /**
     * Returns the FqCN of the form class (e.g. return IntegerType::class;).
     */
    public function getFormType(): string;

    /**
     * Returns an array of form options.
     *
     * @return array<string, mixed>
     */
    public function getFormOptions(): array;

    /**
     * Returns boolean indicating if this form is active.
     */
    public function isActive(): bool;

    /**
     * Returns a list of groups per locale. e.g. ['en' => 'my group', 'de' => 'mein Gruppe'].
     *
     * @return string[]
     */
    public function getGroups(): array;

    /**
     * Returns group for a specific locale.
     */
    public function getGroup(string $locale = ''): string;
}
