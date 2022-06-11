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

namespace Zikula\Bundle\DynamicFormBundle\Tests\Container;

use PHPUnit\Framework\TestCase;
use Zikula\Bundle\DynamicFormBundle\Container\AbstractSpecificationContainer;
use Zikula\Bundle\DynamicFormBundle\Entity\AbstractFormSpecification;

class DynamicPropertiesContainerTest extends TestCase
{
    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Container\AbstractSpecificationContainer::getFormSpecifications
     */
    public function testGetPropertySpecifications(): void
    {
        $container = $this->getContainer();
        $this->assertCount(7, $container->getFormSpecifications());
        foreach ($container->getFormSpecifications() as $spec) {
            $this->assertInstanceOf(AbstractFormSpecification::class, $spec);
        }
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Container\AbstractSpecificationContainer::getLabels
     */
    public function testGetLabels(): void
    {
        $container = $this->getContainer();
        $this->assertEquals(
            [
                'one' => 'One',
                'two' => 'Foo2',
                'three' => 'Foo3',
                'four' => 'Four',
                'five' => 'Foo5',
                'six' => 'Foo6',
                'seven' => 'Foo7',
            ],
            $container->getLabels()
        );
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Container\AbstractSpecificationContainer::getLabels
     */
    public function testGetLabelsTranslated(): void
    {
        $container = $this->getContainer();
        $this->assertEquals(
            [
                'one' => 'One',
                'two' => 'deFoo2',
                'three' => 'Foo3',
                'four' => 'Four',
                'five' => 'Foo5',
                'six' => 'deFoo6',
                'seven' => 'deFoo7',
            ],
            $container->getLabels('de')
        );
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Container\AbstractSpecificationContainer::getGroupedLabels
     */
    public function testGetGroupedLabels(): void
    {
        $container = $this->getContainer();
        $this->assertEquals(
            [
                'Default' => [
                    'one' => 'One',
                    'two' => 'Foo2',
                ],
                'g1' => [
                    'three' => 'Foo3',
                    'four' => 'Four',
                ],
                'g2' => [
                    'five' => 'Foo5',
                    'six' => 'Foo6',
                    'seven' => 'Foo7',
                ],
            ],
            $container->getGroupedLabels()
        );
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Container\AbstractSpecificationContainer::getGroupedLabels
     */
    public function testGetGroupedLabelsTranslated(): void
    {
        $container = $this->getContainer();
        $this->assertEquals(
            [
                'Default' => [
                    'one' => 'One',
                    'two' => 'deFoo2',
                ],
                'g1' => [
                    'three' => 'Foo3',
                    'four' => 'Four',
                ],
                'g2' => [
                    'five' => 'Foo5',
                    ],
                'deG2' => [
                    'six' => 'deFoo6',
                    'seven' => 'deFoo7',
                ],
            ],
            $container->getGroupedLabels('de')
        );
    }

    private function getContainer(): AbstractSpecificationContainer
    {
        return new class() extends AbstractSpecificationContainer {
            public function getFormSpecifications(array $params = []): array
            {
                $properties = [
                    ['name' => 'one', 'labels' => [], 'groups' => []],
                    ['name' => 'two', 'labels' => ['default' => 'Foo2', 'de' => 'deFoo2'], 'groups' => []],
                    ['name' => 'three', 'labels' => ['default' => 'Foo3'], 'groups' => ['default' => 'g1']],
                    ['name' => 'four', 'labels' => [], 'groups' => ['default' => 'g1']],
                    ['name' => 'five', 'labels' => ['default' => 'Foo5'], 'groups' => ['default' => 'g2']],
                    ['name' => 'six', 'labels' => ['default' => 'Foo6', 'es' => 'esFoo6', 'de' => 'deFoo6'], 'groups' => ['default' => 'g2', 'de' => 'deG2']],
                    ['name' => 'seven', 'labels' => ['default' => 'Foo7', 'de' => 'deFoo7'], 'groups' => ['default' => 'g2', 'de' => 'deG2']],
                ];
                $specs = [];
                foreach ($properties as $property) {
                    $spec = new class() extends AbstractFormSpecification {
                    };
                    $spec->setName($property['name']);
                    $spec->setLabels($property['labels']);
                    $spec->setGroups($property['groups']);
                    $specs[] = $spec;
                }

                return $specs;
            }
        };
    }
}
