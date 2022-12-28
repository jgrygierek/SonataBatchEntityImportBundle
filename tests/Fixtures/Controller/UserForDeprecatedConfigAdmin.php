<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests\Fixtures\Controller;

use JG\SonataBatchEntityImportBundle\Admin\AdminWithImportInterface;
use JG\SonataBatchEntityImportBundle\Tests\Fixtures\Configuration\UserImportConfiguration;
use Sonata\AdminBundle\Admin\AbstractAdmin;

class UserForDeprecatedConfigAdmin extends AbstractAdmin implements AdminWithImportInterface
{
    protected $baseRoutePattern = 'user_for_deprecated_config';

    public function getImportConfigurationClassName(): string
    {
        return UserImportConfiguration::class;
    }
}
