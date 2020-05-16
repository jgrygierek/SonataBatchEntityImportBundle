<?php

namespace JG\SonataBatchEntityImportBundle\Tests\Twig;

use JG\SonataBatchEntityImportBundle\Twig\TemplateExtension;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TemplateExtensionTest extends WebTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testValidTemplates(): void
    {
        /** @var TemplateExtension $extension */
        $extension = self::$kernel->getContainer()->get(TemplateExtension::class);

        $this->assertNotEmpty($extension->getTemplate('select_file'));
        $this->assertNotEmpty($extension->getTemplate('edit_matrix'));
        $this->assertNotEmpty($extension->getTemplate('layout'));
        $this->assertNotEmpty($extension->getTemplate('button'));
    }

    public function testWrongTemplateException(): void
    {
        $this->expectExceptionMessage("Template with key 'wrong_template' not found.");

        /** @var TemplateExtension $extension */
        $extension = self::$kernel->getContainer()->get(TemplateExtension::class);
        $extension->getTemplate('wrong_template');
    }
}
