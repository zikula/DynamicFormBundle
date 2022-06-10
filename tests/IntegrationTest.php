<?php

namespace Zikula\Bundle\DynamicFormBundle\Tests;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Zikula\Bundle\DynamicFormBundle\ZikulaDynamicFormBundle;

class IntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return DynamicFormTestKernel::class;
    }

    public function testServicesAvailable(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->assertTrue($container->has('zikula.dynamic_form.event_subscriber.form_type_choice_event_subscriber'));
        $this->assertTrue($container->has('zikula.dynamic_form.form_type.dynamic_form_field_type'));
        $this->assertTrue($container->has('zikula.dynamic_form.form_type.inline_form_definition_type'));
        $this->assertTrue($container->has('zikula.dynamic_form.form_type.translation_collection_type'));
        $this->assertTrue($container->has('zikula.dynamic_form.provider.locale_provider'));

        $this->assertTrue($container->hasParameter('twig.form.resources'));
        /** @var string[] $twigFormResources */
        $twigFormResources = $container->getParameter('twig.form.resources');
        $this->assertContains('@ZikulaDynamicFormProperty/Form/fields.html.twig', $twigFormResources);
    }
}

class DynamicFormTestKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new ZikulaDynamicFormBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('framework', ['secret' => 'not-secret', 'test' => true]);
        });
    }
}
