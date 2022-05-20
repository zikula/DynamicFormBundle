<?php

namespace Zikula\Bundle\DynamicFormPropertyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('zikula_dynamic_form_property');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('translate_labels')
                    ->defaultFalse()
                    ->info('Enable translatable labels for dynamic fields.')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

}