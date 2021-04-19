<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests\Fixtures\Configuration;

use JG\BatchEntityImportBundle\Model\Configuration\AbstractImportConfiguration;
use JG\SonataBatchEntityImportBundle\Tests\Fixtures\Entity\User;

class UserImportConfiguration extends AbstractImportConfiguration
{
    public function getEntityClassName(): string
    {
        return User::class;
    }
}
