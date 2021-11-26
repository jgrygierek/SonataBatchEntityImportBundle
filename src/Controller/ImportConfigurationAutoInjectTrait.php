<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Controller;

use JG\BatchEntityImportBundle\Model\Configuration\ImportConfigurationInterface;

/**
 * @property ImportConfigurationInterface $importConfiguration
 */
trait ImportConfigurationAutoInjectTrait
{
    /**
     * @var array|ImportConfigurationInterface[]
     */
    protected array $importConfigurations = [];

    public function setImportConfiguration(array $importConfigurations): void
    {
        $this->importConfigurations = $importConfigurations;
    }
}
