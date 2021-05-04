<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests\DependencyInjection\Compiler;

use Generator;
use JG\SonataBatchEntityImportBundle\Controller\ImportCrudController;
use JG\SonataBatchEntityImportBundle\DependencyInjection\Compiler\AutoConfigureCompilerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AutoConfigureCompilerPassTest extends AbstractCompilerPassTestCase
{
    private const CONTROLLER_ARGUMENT_INDEX = 2;
    private const CUSTOM_NAMESPACE = 'Namespace\To\Custom\Controller';

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new AutoConfigureCompilerPass());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testControllerReplaced(string $currentController, string $expectedController, array $tags): void
    {
        $controllerDefinition = new Definition('', ['', '', $currentController]);
        foreach ($tags as $tag) {
            $controllerDefinition->addTag($tag);
        }

        $this->setDefinition('sonata_admin', $controllerDefinition);
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'sonata_admin',
            self::CONTROLLER_ARGUMENT_INDEX,
            $expectedController
        );
    }

    public function dataProvider(): Generator
    {
        yield [CRUDController::class, ImportCrudController::class, ['sonata.admin', 'sonata.batch_entity_import.admin']];
        yield [CRUDController::class, CRUDController::class, ['sonata.admin']];
        yield [CRUDController::class, CRUDController::class, ['sonata.batch_entity_import.admin']];
        yield [self::CUSTOM_NAMESPACE, self::CUSTOM_NAMESPACE, ['sonata.admin', 'sonata.batch_entity_import.admin']];
    }
}
