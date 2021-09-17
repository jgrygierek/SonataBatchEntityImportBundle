<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests\DependencyInjection;

use JG\SonataBatchEntityImportBundle\Admin\AdminWithImportInterface;
use JG\SonataBatchEntityImportBundle\DependencyInjection\SonataBatchEntityImportExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class SonataBatchEntityImportExtensionTest extends AbstractExtensionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->load();
    }

    public function testAdminInterfaceHasTag(): void
    {
        $autoConfiguredInstances = $this->container->getAutoconfiguredInstanceof();
        self::assertArrayHasKey(AdminWithImportInterface::class, $autoConfiguredInstances);

        $instance = $autoConfiguredInstances[AdminWithImportInterface::class];
        self::assertNotEmpty($instance->getTag('sonata.batch_entity_import.admin'));
    }

    protected function getContainerExtensions(): array
    {
        return [new SonataBatchEntityImportExtension()];
    }
}
