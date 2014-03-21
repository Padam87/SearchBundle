<?php

namespace Padam87\SearchBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ConverterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('padam87_search.converter.manager')) {
            return;
        }

        $definition     = $container->getDefinition('padam87_search.converter.manager');
        $taggedServices = $container->findTaggedServiceIds('padam87_search.converter');

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall('addConverter', array(new Reference($id)));
        }
    }
}