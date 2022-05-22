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
use Zikula\Bundle\DynamicFormPropertyBundle\DynamicPropertyDataInterface;

/**
 * @ORM\MappedSuperclass
 */
#[ORM\MappedSuperclass]
abstract class AbstractDynamicPropertyData implements DynamicPropertyDataInterface
{
    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(type: 'json', nullable: true)]
    protected array $data = [];

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function __unset(string $name): void
    {
        unset($this->data[$name]);
    }
}
