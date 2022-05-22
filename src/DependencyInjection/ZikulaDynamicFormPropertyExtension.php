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

namespace Zikula\Bundle\DynamicFormPropertyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ZikulaDynamicFormPropertyExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__).'/../config'));
        $loader->load('services.php');

        $configuration = $this->getConfiguration($configs, $container);
        if (null !== $configuration) {
            $config = $this->processConfiguration($configuration, $configs);
            $definition = $container->getDefinition('zikula.dynamic_form.provider.locale_provider');
            $definition->setArgument(1, $config['translate_labels']);

            $definition = $container->getDefinition('zikula.dynamic_form.form_type.dynamic_form_field_type');
            $definition->setArgument(1, $config['translate_labels']);
        }

        $this->registerFormTheme($container);
    }

    private function registerFormTheme(ContainerBuilder $container): void
    {
        /** @var string[] $resources */
        $resources = $container->hasParameter('twig.form.resources') ?
            $container->getParameter('twig.form.resources') : [];

        \array_unshift($resources, '@ZikulaDynamicFormProperty/Form/fields.html.twig');
        $container->setParameter('twig.form.resources', $resources);
    }
}
