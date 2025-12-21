<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sonata_batch_entity_import');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $this->addTemplatesConfig($rootNode->children());

        return $treeBuilder;
    }

    /**
     * @param NodeBuilder<ArrayNodeDefinition> $parentBuilder
     */
    private function addTemplatesConfig(NodeBuilder $parentBuilder): void
    {
        $builder = $parentBuilder->arrayNode('templates')->addDefaultsIfNotSet()->children();

        $this->addNodeConfig($builder, 'select_file', '@SonataBatchEntityImport/select_file.html.twig');
        $this->addNodeConfig($builder, 'edit_matrix', '@SonataBatchEntityImport/edit_matrix.html.twig');
        $this->addNodeConfig($builder, 'button', '@SonataBatchEntityImport/button.html.twig');
    }

    /**
     * @param NodeBuilder<ArrayNodeDefinition> $builder
     */
    private function addNodeConfig(NodeBuilder $builder, string $name, string $value): void
    {
        $builder
            ->scalarNode($name)
            ->defaultValue($value)
            ->cannotBeEmpty()
            ->end();
    }
}
