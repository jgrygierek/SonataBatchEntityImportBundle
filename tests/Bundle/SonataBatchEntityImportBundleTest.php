<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests\Bundle;

use JG\SonataBatchEntityImportBundle\SonataBatchEntityImportBundle;
use PHPUnit\Framework\TestCase;
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
}
