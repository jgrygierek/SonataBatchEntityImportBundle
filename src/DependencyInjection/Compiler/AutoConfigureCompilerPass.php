<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\DependencyInjection\Compiler;

use JG\SonataBatchEntityImportBundle\Controller\ImportCrudController;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AutoConfigureCompilerPass implements CompilerPassInterface
{
    private const CONTROLLER_ARGUMENT_INDEX = 2;

    public function process(ContainerBuilder $container): void
    {
        $taggedServices = $container->findTaggedServiceIds('sonata.batch_entity_import.admin');
        foreach ($taggedServices as $id => $tags) {
            $this->replaceDefaultControllerInAdminServices($container->getDefinition($id));
        }
    }

    private function replaceDefaultControllerInAdminServices(Definition $definition): void
    {
        if ($this->canDefaultControllerBeReplaced($definition)) {
            $definition->setArgument(self::CONTROLLER_ARGUMENT_INDEX, ImportCrudController::class);
        }
    }

    private function canDefaultControllerBeReplaced(Definition $definition): bool
    {
        return $definition->hasTag('sonata.admin')
            && $definition->getArgument(self::CONTROLLER_ARGUMENT_INDEX) === CRUDController::class;
    }
}
