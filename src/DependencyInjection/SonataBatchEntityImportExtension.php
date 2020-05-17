<?php

namespace JG\SonataBatchEntityImportBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SonataBatchEntityImportExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $this->setParameters($configs, $container);
    }

    private function setParameters(array $configs, ContainerBuilder $container): void
    {
        $processor     = new Processor();
        $configuration = new Configuration();
        $config        = $processor->processConfiguration($configuration, $configs);

        $templates = $config['templates'];
        $container->setParameter('sonata_batch_entity_import.templates', $templates);
        $container->setParameter('sonata_batch_entity_import.templates.select_file', $templates['select_file']);
        $container->setParameter('sonata_batch_entity_import.templates.edit_matrix', $templates['edit_matrix']);
    }
}
