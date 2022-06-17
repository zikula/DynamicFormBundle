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

namespace Zikula\Bundle\DynamicFormBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Zikula\Bundle\DynamicFormBundle\Entity\AbstractFormSpecification;

class AbstractFormSpecificationTest extends TestCase
{
    private AbstractFormSpecification $formSpecification;

    protected function setUp(): void
    {
        $this->formSpecification = new class() extends AbstractFormSpecification {
        };
    }

    public function testSetLabels(): void
    {
        $this->assertEmpty($this->formSpecification->getLabels());
        $labels = [
            'default' => 'foo',
            'de' => 'de_foo',
            'es' => 'es_foo',
        ];
        $this->formSpecification->setLabels($labels);
        $this->assertNotEmpty($this->formSpecification->getLabels());
    }

    public function testGetLabel(): void
    {
        $this->formSpecification->setName('field');
        $this->formSpecification->setLabels([]);
        $this->assertEmpty($this->formSpecification->getLabels());
        $this->assertSame('Field', $this->formSpecification->getLabel());

        $labels = [
            'default' => 'foo',
            'de' => 'de_foo',
            'es' => 'es_foo',
        ];
        $this->formSpecification->setLabels($labels);
        $this->assertSame('foo', $this->formSpecification->getLabel());
        $this->assertSame('de_foo', $this->formSpecification->getLabel('de'));

        $labels = [
            'de' => 'de_foo',
            'es' => 'es_foo',
        ];
        $this->formSpecification->setLabels($labels);
        $this->assertSame('de_foo', $this->formSpecification->getLabel());
    }

    public function testGetLabels(): void
    {
        $labels = [
            'default' => 'foo',
            'de' => 'de_foo',
            'es' => 'es_foo',
        ];
        $this->formSpecification->setLabels($labels);
        $this->assertSame($labels, $this->formSpecification->getLabels());
    }

    public function testSetGroups(): void
    {
        $this->assertEmpty($this->formSpecification->getLabels());
        $groups = [
            'g1' => 'foo',
            'g2' => 'bar',
        ];
        $this->formSpecification->setGroups($groups);
        $this->assertNotEmpty($this->formSpecification->getGroups());
    }

    public function testGetGroup(): void
    {
        $this->assertSame('Default', $this->formSpecification->getGroup());

        $groups = [
            'default' => 'Default Group',
            'g1' => 'foo',
            'g2' => 'bar',
        ];
        $this->formSpecification->setGroups($groups);
        $this->assertSame('Default Group', $this->formSpecification->getGroup());

        $groups = [
            'g1' => 'foo',
            'g2' => 'bar',
        ];
        $this->formSpecification->setGroups($groups);
        $this->assertSame('Default', $this->formSpecification->getGroup());
        $this->assertSame('foo', $this->formSpecification->getGroup('g1'));
    }

    public function testGetGroups(): void
    {
        $groups = [
            'g1' => 'foo',
            'g2' => 'bar',
        ];
        $this->formSpecification->setGroups($groups);
        $this->assertSame($groups, $this->formSpecification->getGroups());
    }
}
