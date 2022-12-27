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
     * @dataProvider dataProviderForConfigurationInArguments
     */
    public function testControllerReplacedWhenInArguments(string $currentController, string $expectedController, array $tags): void
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
            $expectedController,
        );
    }

    /**
     * @dataProvider dataProviderForConfigurationInTags
     */
    public function testControllerReplacedWhenInTags(?string $currentController, array $expectedMethodCalls, array $tags): void
    {
        $controllerDefinition = new Definition('');
        foreach ($tags as $tag) {
            if ('sonata.admin' !== $tag) {
                $controllerDefinition->addTag($tag);
            }
        }

        $tagAttributes = ['model_class' => 'Test\Entity\Name'];
        if ($currentController) {
            $tagAttributes['controller'] = $currentController;
        }

        $controllerDefinition->addTag('sonata.admin', $tagAttributes);

        $this->setDefinition('sonata_admin', $controllerDefinition);
        $this->compile();

        $this->assertSame($expectedMethodCalls, $controllerDefinition->getMethodCalls());
    }

    public function testDoNotReplaceIfNoSonataAdminTag(): void
    {
        $controllerDefinition = new Definition('');
        $controllerDefinition->addTag('sonata.batch_entity_import.admin');

        $this->setDefinition('sonata_admin', $controllerDefinition);
        $this->compile();

        $this->assertSame([], $controllerDefinition->getMethodCalls());
        $this->assertSame([], $controllerDefinition->getArguments());
    }

    public function dataProviderForConfigurationInArguments(): Generator
    {
        yield [CRUDController::class, ImportCrudController::class, ['sonata.admin', 'sonata.batch_entity_import.admin']];
        yield [CRUDController::class, CRUDController::class, ['sonata.admin']];
        yield [CRUDController::class, CRUDController::class, ['sonata.batch_entity_import.admin']];
        yield ['sonata.admin.controller.crud', ImportCrudController::class, ['sonata.admin', 'sonata.batch_entity_import.admin']];
        yield ['sonata.admin.controller.crud', 'sonata.admin.controller.crud', ['sonata.admin']];
        yield ['sonata.admin.controller.crud', 'sonata.admin.controller.crud', ['sonata.batch_entity_import.admin']];
        yield [self::CUSTOM_NAMESPACE, self::CUSTOM_NAMESPACE, ['sonata.admin', 'sonata.batch_entity_import.admin']];
        yield ['SonataAdminBundle:CRUD', ImportCrudController::class, ['sonata.admin', 'sonata.batch_entity_import.admin']];
        yield ['SonataAdminBundle:CRUD', 'SonataAdminBundle:CRUD', ['sonata.admin']];
        yield ['SonataAdminBundle:CRUD', 'SonataAdminBundle:CRUD', ['sonata.batch_entity_import.admin']];
    }

    public function dataProviderForConfigurationInTags(): Generator
    {
        yield [null, [['setBaseControllerName', [ImportCrudController::class]]], ['sonata.batch_entity_import.admin']];
        yield [null, [], ['sonata.admin']];
        yield [CRUDController::class, [], ['sonata.admin']];
        yield [CRUDController::class, [['setBaseControllerName', [ImportCrudController::class]]], ['sonata.batch_entity_import.admin']];
        yield ['sonata.admin.controller.crud', [], ['sonata.admin']];
        yield ['sonata.admin.controller.crud', [['setBaseControllerName', [ImportCrudController::class]]], ['sonata.batch_entity_import.admin']];
        yield [self::CUSTOM_NAMESPACE, [], ['sonata.admin', 'sonata.batch_entity_import.admin']];
        yield ['SonataAdminBundle:CRUD', [], ['sonata.admin']];
        yield ['SonataAdminBundle:CRUD', [['setBaseControllerName', [ImportCrudController::class]]], ['sonata.batch_entity_import.admin']];
    }
}
