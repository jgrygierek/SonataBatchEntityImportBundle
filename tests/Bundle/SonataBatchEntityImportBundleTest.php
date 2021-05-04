<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests\Bundle;

use JG\SonataBatchEntityImportBundle\DependencyInjection\Compiler\AutoConfigureCompilerPass;
use JG\SonataBatchEntityImportBundle\SonataBatchEntityImportBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SonataBatchEntityImportBundleTest extends TestCase
{
    private SonataBatchEntityImportBundle $bundle;

    protected function setUp(): void
    {
        $this->bundle = new SonataBatchEntityImportBundle();
    }

    public function testBundle(): void
    {
        self::assertInstanceOf(Bundle::class, $this->bundle);
    }

    public function testBundleBuild(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $containerBuilder
            ->expects(self::once())
            ->method('addCompilerPass')
            ->with(new AutoConfigureCompilerPass());

        $this->bundle->build($containerBuilder);
    }
}
