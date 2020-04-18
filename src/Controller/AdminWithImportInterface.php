<?php

namespace JG\SonataBatchEntityImportBundle\Controller;

interface AdminWithImportInterface
{
    public function getImportConfigurationClassName(): string;
}
