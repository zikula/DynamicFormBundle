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

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Translation\IdentityTranslator;
use Zikula\Bundle\DynamicFormBundle\Entity\AbstractFormSpecification;
use Zikula\Bundle\DynamicFormBundle\EventSubscriber\FormTypeChoiceEventSubscriber;
use Zikula\Bundle\DynamicFormBundle\Form\Type\FormSpecificationCollectionType;
use Zikula\Bundle\DynamicFormBundle\Form\Type\FormSpecificationType;

class FormSpecificationCollectionTypeTest extends TypeTestCase
{
    /**
     * @return FormExtensionInterface[]
     */
    protected function getExtensions(): array
    {
        $dispatcher = new EventDispatcher();
        $translator = new IdentityTranslator();
        $dispatcher->addSubscriber(new FormTypeChoiceEventSubscriber($translator));
        $type = new FormSpecificationType($dispatcher, false);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testBasicFormSubmission(): void
    {
        $form = $this->factory->create(FormSpecificationCollectionType::class, [new q(), new q()], [
            'entry_options' => [
                'data_class' => q::class,
            ],
        ]);
        $this->assertTrue($form->isSynchronized());
        $form->submit([['name' => 'f1', 'formType' => IntegerType::class], ['name' => 'f2', 'formType' => IntegerType::class]]);
        $this->assertEmpty($form->getErrors());
        $data = $form->getData();
        $this->assertCount(2, $data);
        $this->assertSame('f1', $data[0]->getName());
        $this->assertSame('f2', $data[1]->getName());
    }
}

class q extends AbstractFormSpecification
{
}
