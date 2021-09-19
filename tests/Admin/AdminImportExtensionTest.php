<?php

declare(strict_types=1);

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

    protected function setUp(): void
    {
        $this->extension = new AdminImportExtension(
            [
                'button' => '@SonataBatchEntityImport/button.html.twig',
            ]
        );
    }

    public function testConfiguredRoutesForImportAdmin(): void
    {
        $routeCollection = new RouteCollection('', '', '', '');
        $this->extension->configureRoutes($this->getAdminWithImport(), $routeCollection);

        self::assertTrue($routeCollection->has('import'));
        self::assertTrue($routeCollection->has('import_save'));
    }

    public function testConfigureRoutesForNormalAdmin(): void
    {
        $routeCollection = new RouteCollection('', '', '', '');
        $this->extension->configureRoutes($this->getAdmin(), $routeCollection);

        self::assertFalse($routeCollection->has('import'));
        self::assertFalse($routeCollection->has('import_save'));
    }

    public function testConfigureActionButtonsForImportAdmin(): void
    {
        $result = $this->extension->configureActionButtons($this->getAdminWithImport(), [], '');

        self::assertArrayHasKey('import', $result);
        self::assertArrayHasKey('template', $result['import']);
        self::assertEquals('@SonataBatchEntityImport/button.html.twig', $result['import']['template']);
    }

    public function testConfigureActionButtonsForNormalAdmin(): void
    {
        $result = $this->extension->configureActionButtons($this->getAdmin(), [], '');

        self::assertArrayNotHasKey('import', $result);
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
