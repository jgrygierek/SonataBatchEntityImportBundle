<?php

namespace JG\SonataBatchEntityImportBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use UnexpectedValueException;

class TemplateExtension extends AbstractExtension
{
    private array  $availableTemplates;

    public function __construct(array $availableTemplates = [])
    {
        $this->availableTemplates = $availableTemplates;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sonata_batch_entity_import_template', [$this, 'getTemplate']),
        ];
    }

    public function getTemplate(string $name): string
    {
        if (array_key_exists($name, $this->availableTemplates)) {
            return $this->availableTemplates[$name];
        }

        throw new UnexpectedValueException("Template with key '$name' not found.");
    }
}
