<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Admin;

interface AdminWithImportInterface
{
    public function getImportConfigurationClassName(): string;
}
