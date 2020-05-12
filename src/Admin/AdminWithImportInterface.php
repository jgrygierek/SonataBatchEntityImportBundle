<?php

namespace JG\SonataBatchEntityImportBundle\Admin;

interface AdminWithImportInterface
{
    public function getImportConfigurationClassName(): string;
}
