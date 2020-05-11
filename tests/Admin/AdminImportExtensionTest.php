<?php

namespace JG\SonataBatchEntityImportBundle\Tests\Admin;

use JG\SonataBatchEntityImportBundle\Admin\AdminImportExtension;
use JG\SonataBatchEntityImportBundle\Admin\AdminWithImportInterface;
use PHPUnit\Framework\TestCase;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class AdminImportExtensionTest extends TestCase
{
    private AdminImportExtension $extension;

    public function setUp(): void
    {
        $this->extension = new AdminImportExtension();
    }

    public function testConfiguredRoutesForImportAdmin(): void
    {
        $routeCollection = new RouteCollection('', '', '', '');
        $this->extension->configureRoutes($this->getAdminWithImport(), $routeCollection);

        $this->assertTrue($routeCollection->has('import'));
        $this->assertTrue($routeCollection->has('import_save'));
    }

    public function testConfigureRoutesForNormalAdmin(): void
    {
        $routeCollection = new RouteCollection('', '', '', '');
        $this->extension->configureRoutes($this->getAdmin(), $routeCollection);

        $this->assertFalse($routeCollection->has('import'));
        $this->assertFalse($routeCollection->has('import_save'));
    }

    public function testConfigureActionButtonsForImportAdmin(): void
    {
        $result = $this->extension->configureActionButtons($this->getAdminWithImport(), [], null, null);

        $this->assertArrayHasKey('import', $result);
        $this->assertArrayHasKey('template', $result['import']);
    }

    public function testConfigureActionButtonsForNormalAdmin(): void
    {
        $result = $this->extension->configureActionButtons($this->getAdmin(), [], null, null);

        $this->assertArrayNotHasKey('import', $result);
    }

    private function getAdmin(): AdminInterface
    {
        return new class('', '', '') extends AbstractAdmin {
        };
    }

    private function getAdminWithImport(): AdminInterface
    {
        return new class('', '', '') extends AbstractAdmin implements AdminWithImportInterface {
            public function getImportConfigurationClassName(): string
            {
                return '';
            }
        };
    }
}
