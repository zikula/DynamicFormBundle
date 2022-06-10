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

namespace Zikula\Bundle\DynamicFormBundle\Tests\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\DynamicFormBundle\Container\AbstractDynamicPropertiesContainer;
use Zikula\Bundle\DynamicFormBundle\Entity\AbstractDynamicPropertyData;
use Zikula\Bundle\DynamicFormBundle\Entity\AbstractDynamicPropertySpecification;
use Zikula\Bundle\DynamicFormBundle\Form\Type\ChoiceTypeTransformed;
use Zikula\Bundle\DynamicFormBundle\Form\Type\InlineFormDefinitionType;

class InlineFormDefinitionTypeTest extends TypeTestCase
{
    private TranslatorInterface $translator;

    protected function setUp(): void
    {
        $this->translator = new class() implements TranslatorInterface {
            /**
             * @param string[] $parameters this is for phpstan
             */
            public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null)
            {
                return $id;
            }

            public function getLocale(): string
            {
                return 'default';
            }
        };
        parent::setUp();
    }

    /**
     * @return FormExtensionInterface[]
     */
    protected function getExtensions(): array
    {
        $type = new InlineFormDefinitionType($this->translator);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    /**
     * @covers \Zikula\Bundle\DynamicFormBundle\Form\Type\InlineFormDefinitionType
     */
    public function testSubmitValidData(): void
    {
        $options = ['dynamicFieldsContainer' => new TempContainer()];
        $result = new class() extends AbstractDynamicPropertyData {
        };
        $form = $this->factory->create(ParentFormType::class, $result, $options);
        $this->assertTrue($form->get('fields')->has('name'));
        $this->assertTrue($form->get('fields')->has('date'));
        $this->assertTrue($form->get('fields')->has('color'));
        $this->assertInstanceOf(TextType::class, $form->get('fields')->get('name')->getConfig()->getType()->getInnerType());
        $this->assertInstanceOf(DateType::class, $form->get('fields')->get('date')->getConfig()->getType()->getInnerType());
        $this->assertInstanceOf(ChoiceTypeTransformed::class, $form->get('fields')->get('color')->getConfig()->getType()->getInnerType());

        $this->assertEmpty($result->getData());
        $formData = ['fields' => ['name' => 'john', 'date' => '2022-01-01', 'color' => 'red']];
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData['fields'], $result->getData());
    }
}

class ParentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fields', InlineFormDefinitionType::class, [
                'dynamicFieldsContainer' => $options['dynamicFieldsContainer'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('dynamicFieldsContainer');
    }
}

class TempContainer extends AbstractDynamicPropertiesContainer
{
    /**
     * @param array<string, mixed> $options
     */
    public function getSpec(string $name, string $type, array $options = []): AbstractDynamicPropertySpecification
    {
        $spec = new class() extends AbstractDynamicPropertySpecification {
        };
        $spec->setName($name);
        $spec->setFormType($type);
        $spec->setFormOptions($options);
        $spec->setActive(true);

        return $spec;
    }

    public function getPropertySpecifications(array $params = []): array
    {
        return [
            $this->getSpec('name', TextType::class),
            $this->getSpec('date', DateType::class, ['widget' => 'single_text', 'html5' => false, 'input' => 'string']),
            $this->getSpec('color', ChoiceTypeTransformed::class, ['choices' => ['blue', 'red', 'yellow']]),
        ];
    }
}
