<?php

namespace Padam87\SearchBundle;

use Padam87\SearchBundle\DependencyInjection\Compiler\ConverterCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Padam87SearchBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ConverterCompilerPass());
    }
}
