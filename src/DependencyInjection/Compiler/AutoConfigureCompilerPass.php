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
        $tags = $definition->getTag('sonata.admin');

        if (isset($tags[0]['model_class'])) {
            if ($this->canDefaultControllerBeReplacedInServiceTags($definition)) {
                $definition->addMethodCall('setBaseControllerName', [ImportCrudController::class]);
            }
        } elseif ($this->canDefaultControllerBeReplacedInServiceArguments($definition)) {
            $definition->setArgument(self::CONTROLLER_ARGUMENT_INDEX, ImportCrudController::class);
        }
    }

    private function canDefaultControllerBeReplacedInServiceTags(Definition $definition): bool
    {
        return $definition->hasTag('sonata.admin') && $this->isDefaultControllerUsedInServiceTags($definition);
    }

    private function isDefaultControllerUsedInServiceTags(Definition $definition): bool
    {
        $tags = $definition->getTag('sonata.admin')[0];
        if (!\array_key_exists('model_class', $tags)) {
            return false;
        }

        return !isset($tags['controller']) || in_array($tags['controller'], $this->getDefaultControllerNames(), true);
    }

    private function canDefaultControllerBeReplacedInServiceArguments(Definition $definition): bool
    {
        return $definition->hasTag('sonata.admin') && $this->isDefaultControllerUsedInServiceArguments($definition);
    }

    private function isDefaultControllerUsedInServiceArguments(Definition $definition): bool
    {
        return in_array($definition->getArgument(self::CONTROLLER_ARGUMENT_INDEX), $this->getDefaultControllerNames(), true);
    }

    private function getDefaultControllerNames(): array
    {
        return [
            'SonataAdminBundle:CRUD',
            'sonata.admin.controller.crud',
            CRUDController::class,
        ];
    }
}
