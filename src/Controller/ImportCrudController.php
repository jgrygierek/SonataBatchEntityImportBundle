<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Controller;

use JG\BatchEntityImportBundle\Controller\ImportConfigurationAutoInjectInterface;
use JG\BatchEntityImportBundle\Model\Configuration\ImportConfigurationInterface;
use JG\SonataBatchEntityImportBundle\Admin\AdminWithImportInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use UnexpectedValueException;

/**
 * @property AdminInterface|AdminWithImportInterface $admin
 */
class ImportCrudController extends CRUDController implements ImportConfigurationAutoInjectInterface
{
    use ImportConfigurationAutoInjectTrait;
    use ImportControllerTrait;

    protected function getImportConfiguration(): ImportConfigurationInterface
    {
        if (!$this->importConfiguration instanceof ImportConfigurationInterface) {
            $class = $this->getImportConfigurationClassName();
            if (!class_exists($class)) {
                throw new UnexpectedValueException('Configuration class not found.');
            }

            if (!isset($this->importConfigurations[$class])) {
                throw new ServiceNotFoundException($class);
            }

            $this->importConfiguration = $this->importConfigurations[$class];
        }

        return $this->importConfiguration;
    }
}
