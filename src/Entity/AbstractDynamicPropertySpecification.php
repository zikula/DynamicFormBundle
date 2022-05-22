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

namespace Zikula\Bundle\DynamicFormPropertyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Bundle\DynamicFormPropertyBundle\DynamicPropertySpecificationInterface;

/**
 * @ORM\MappedSuperclass
 */
#[ORM\MappedSuperclass]
abstract class AbstractDynamicPropertySpecification implements DynamicPropertySpecificationInterface
{
    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\Length(min="1", max="255")
     * @Assert\Regex(pattern="/^\w+$/", message="The name can only contain letters and underscores.")
     */
    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\Length(min: 1, max: 255)]
    #[Assert\Regex(pattern: "/^\w+$/", message: 'The name can only contain letters and underscores.')]
    protected ?string $name = null;

    /**
     * @var array<string, string>
     * @ORM\Column(type="array", nullable=false)
     * @Assert\NotNull()
     */
    #[ORM\Column(type: 'array', nullable: false)]
    #[Assert\NotNull]
    protected array $labels = [];

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\Length(min="1", max="255")
     */
    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\Length(min: 1, max: 255)]
    protected string $formType = '';

    /**
     * @var array<string, mixed>
     * @ORM\Column(type="array")
     * @Assert\NotNull()
     */
    #[ORM\Column(type: 'array')]
    #[Assert\NotNull]
    protected array $formOptions = [];

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    #[ORM\Column(type: 'integer')]
    #[Assert\PositiveOrZero]
    protected int $weight = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    #[ORM\Column(type: 'boolean')]
    protected bool $active = true;

    /**
     * @return array<string, string>
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getLabel(string $locale = ''): string
    {
        if (!empty($locale) && isset($this->labels[$locale])) {
            return $this->labels[$locale];
        }
        if (isset($this->labels['default'])) {
            return $this->labels['default'];
        }
        $values = array_values($this->labels);

        return !empty($values[0]) ? array_shift($values) : ucfirst($this->name);
    }

    /**
     * @param array<string, string> $labels
     */
    public function setLabels(array $labels): void
    {
        $this->labels = $labels;
    }

    public function getFormType(): string
    {
        return $this->formType;
    }

    public function setFormType(string $formType): void
    {
        $this->formType = $formType;
    }

    /**
     * @return array<string, mixed>
     */
    public function getFormOptions(): array
    {
        if (!isset($this->formOptions['required'])) {
            $this->formOptions['required'] = false;
        }

        return $this->formOptions;
    }

    /**
     * @param array<string, mixed> $formOptions
     */
    public function setFormOptions(array $formOptions): void
    {
        $this->formOptions = $formOptions;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPrefix(): string
    {
        return '';
    }

    public function getGroupNames(): array
    {
        return [];
    }
}
