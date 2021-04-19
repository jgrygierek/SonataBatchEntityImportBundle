<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests\DependencyInjection;

use JG\SonataBatchEntityImportBundle\Controller\ImportCrudController;
use JG\SonataBatchEntityImportBundle\DependencyInjection\SonataBatchEntityImportExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class SonataBatchEntityImportExtensionTest extends AbstractExtensionTestCase
{
    public function testInstanceHasTag(): void
    {
        $this->load();
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            ImportCrudController::class,
            'batch_entity_import.controller'
        );
    }

    protected function getContainerExtensions(): array
    {
        return [new SonataBatchEntityImportExtension()];
    }
}
