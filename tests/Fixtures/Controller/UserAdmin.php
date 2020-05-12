<?php

namespace JG\SonataBatchEntityImportBundle\Tests\Fixtures\Controller;

use JG\SonataBatchEntityImportBundle\Admin\AdminWithImportInterface;
use JG\SonataBatchEntityImportBundle\Tests\Fixtures\Configuration\UserImportConfiguration;
use Sonata\AdminBundle\Admin\AbstractAdmin;

class UserAdmin extends AbstractAdmin implements AdminWithImportInterface
{
    protected $baseRoutePattern = 'user';

    public function getImportConfigurationClassName(): string
    {
        return UserImportConfiguration::class;
    }
}
