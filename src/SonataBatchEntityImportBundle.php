<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle;

use JG\SonataBatchEntityImportBundle\DependencyInjection\Compiler\AutoConfigureCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SonataBatchEntityImportBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new AutoConfigureCompilerPass());
    }
}
