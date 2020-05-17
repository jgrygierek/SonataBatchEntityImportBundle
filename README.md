# SonataBatchEntityImportBundle

[![Build Status](https://img.shields.io/travis/com/jgrygierek/SonataBatchEntityImportBundle/master?style=flat-square)](https://travis-ci.com/jgrygierek/SonataBatchEntityImportBundle) 
[![Code Coverage](https://img.shields.io/codecov/c/github/jgrygierek/SonataBatchEntityImportBundle/master?style=flat-square)](https://codecov.io/gh/jgrygierek/SonataBatchEntityImportBundle)

Bundle is built on top of [BatchEntityImportBundle](https://github.com/jgrygierek/BatchEntityImportBundle).

Importing entities with preview and edit features for Sonata Admin.

* Data can be **viewed and edited** before saving to database.
* Supports **inserting** new records and **updating** existing ones.
* Supported extensions: **CSV, XLS, XLSX, ODS**.
* Supports translations from **KnpLabs Translatable** extension.
* The code is divided into smaller methods that can be easily replaced if you want to change something.
* Columns names are required and should be added as header (first row).
* If column does not have name provided, will be removed from loaded data.

## Documentation
* [Installation](#installation)
* [Basic configuration class](#basic-configuration-class)
* [Creating admin](#creating-admin)
* [Admin service](#admin-service)
* [Translations](#translations)
* [Fields definitions](#fields-definitions)
* [Overriding templates](#overriding-templates)

## Installation

Install package via composer:

```
composer require jgrygierek/sonata-batch-entity-import-bundle
```

Add entry to `bundles.php` file:

```
JG\SonataBatchEntityImportBundle\SonataBatchEntityImportBundle::class => ['all' => true],
```

## Basic configuration class

You have to create configuration class. In the simplest case it will contain only class of used entity.

```php
namespace App\Model\ImportConfiguration;

use App\Entity\User;
use JG\BatchEntityImportBundle\Model\Configuration\AbstractImportConfiguration;

class UserImportConfiguration extends AbstractImportConfiguration
{
    public function getEntityClassName(): string
    {
        return User::class;
    }
}
```

## Creating admin

Your admin class should implement `AdminWithImportInterface` and should contain one additional method.

```php
namespace App\Admin;

use App\Model\ImportConfiguration\UserImportConfiguration;
use JG\SonataBatchEntityImportBundle\Admin\AdminWithImportInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;

class UserAdmin extends AbstractAdmin implements AdminWithImportInterface
{
    public function getImportConfigurationClassName(): string
    {
        return UserImportConfiguration::class;
    }
}
```

## Admin service

In your admin service definition you have to change controller (put it as 3rd argument):

```yaml
services:
    admin.user:
        class: App\Admin\UserAdmin
        arguments:
            - ~
            - App\Entity\User
            - JG\SonataBatchEntityImportBundle\Controller\ImportCrudController
        tags:
            - { name: sonata.admin, manager_type: orm }
```

## Translations

This bundle supports KnpLabs Translatable behavior.

To use this feature, every column with translatable values should be suffixed with locale, for example:
* `name:en`
* `description:pl`
* `title:ru`

If suffix will be added to non-translatable entity, field will be skipped.

If suffix will be added to translatable entity, but field will not be found in translation class, field will be skipped.

## Fields definitions

If you want to change types of rendered fields, instead of using default ones,
you have to override method in your import configuration.

```php

use JG\BatchEntityImportBundle\Model\Form\FormFieldDefinition;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

public function getFieldsDefinitions(): array
{
    return [
        'age' => new FormFieldDefinition(
            IntegerType::class,
            [
                'attr' => [
                    'min' => 0,
                    'max' => 999,
                ],
            ]
        ),
        'name' => new FormFieldDefinition(TextType::class),
        'description' => new FormFieldDefinition(
            TextareaType::class,
            [
                'attr' => [
                    'rows' => 2,
                ],
            ]
        ),
    ];
}
```

## Overriding templates

You have two ways to override templates globally:

- **Configuration** - just change paths to templates in your configuration file. 
Values in this example are default ones and will be used if nothing will be changed.

```yaml
sonata_batch_entity_import:
    templates:
        select_file: '@SonataBatchEntityImport/select_file.html.twig'
        edit_matrix: '@SonataBatchEntityImport/edit_matrix.html.twig'
        button: '@SonataBatchEntityImport/button.html.twig'
```

- **Bundle directory** - put your templates in this directory:

```
templates/bundles/SonataBatchEntityImportBundle
```
