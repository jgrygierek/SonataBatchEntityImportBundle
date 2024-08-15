<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

class AdminImportExtension extends AbstractAdminExtension
{
    public function __construct(private readonly array $availableTemplates = [])
    {
    }

    public function configureRoutes(AdminInterface $admin, RouteCollectionInterface $collection): void
    {
        if (!$admin instanceof AdminWithImportInterface) {
            return;
        }

        $collection->add('import');
        $collection->add('import_save', null, [], [], [], '', [], ['POST']);
    }

    public function configureActionButtons(AdminInterface $admin, array $list, string $action, ?object $object = null): array
    {
        if ($admin instanceof AdminWithImportInterface) {
            $list['import']['template'] = $this->availableTemplates['button'];
        }

        return parent::configureActionButtons($admin, $list, $action, $object);
    }
}
