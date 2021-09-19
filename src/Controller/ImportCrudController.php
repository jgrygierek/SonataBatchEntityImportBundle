<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Controller;

use JG\SonataBatchEntityImportBundle\Admin\AdminWithImportInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Controller\CRUDController;

/**
 * @property AdminInterface|AdminWithImportInterface $admin
 */
class ImportCrudController extends CRUDController
{
    use ImportControllerTrait;
}
