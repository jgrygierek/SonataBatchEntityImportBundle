<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class AdminImportExtension extends AbstractAdminExtension
{
    private array $availableTemplates;

    public function __construct(array $availableTemplates = [])
    {
        $this->availableTemplates = $availableTemplates;
    }

    public function configureRoutes(AdminInterface $admin, RouteCollection $collection): void
    {
        if (!$admin instanceof AdminWithImportInterface) {
            return;
        }

        $collection->add('import');
        $collection->add('import_save', null, [], [], [], '', [], ['POST']);
    }

    public function configureActionButtons(AdminInterface $admin, $list, $action, $object): array
    {
        if ($admin instanceof AdminWithImportInterface) {
            $list['import']['template'] = $this->availableTemplates['button'];
        }

        return parent::configureActionButtons($admin, $list, $action, $object);
    }
}
