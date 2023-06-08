UPGRADE TO 2.6.x
=======================

CSV File
--------------
* Now CSV file can contain spaces and dashes as a header name, for example "my column name" or "my-column-name".

Import Configuration class
--------------
* When header name contains spaces we should use underscores instead of spaces when defining fields names in fields definitions and in constraints.

UPGRADE TO 2.4.x
=======================

Import Configuration class
--------------
* Added new validator to check matrix record data uniqueness in database.
```php
use JG\BatchEntityImportBundle\Validator\Constraints\DatabaseEntityUnique;

public function getMatrixConstraints(): array
{
    return [
        new DatabaseEntityUnique(['entityClassName' => $this->getEntityClassName(), 'fields' => ['field_name']]),
    ];
}
```

Admin service definition
--------------
* Now autoconfiguration works if admin is defined in tags
```yaml
  # Previous version:
  JG\SonataBatchEntityImportBundle\Tests\Fixtures\Controller\UserForDeprecatedConfigAdmin:
    arguments:
      - ~
      -  App\Entity\User
      - ~
    tags:
      - { name: sonata.admin, manager_type: orm, label: 'User' }

  # Now:
  App\Controller\UserAdmin:
    tags:
      - { name: sonata.admin, manager_type: orm, model_class: App\Entity\User, label: 'User' }
```

UPGRADE TO 2.3.x
=======================

Import Configuration class
--------------
* Added new validator to check matrix record data uniqueness.
```php
use JG\BatchEntityImportBundle\Validator\Constraints\MatrixRecordUnique;

public function getMatrixConstraints(): array
{
    return [
        new MatrixRecordUnique(['fields' => ['field_name']]),
    ];
}
```

Controller
--------------
* List of options passed to form in `createMatrixForm()` method, should contain new `constraints` element:
  `'constraints' => $importConfiguration->getMatrixConstraints()`

UPGRADE TO 2.2.x
=======================

Import Configuration class
--------------

* Now configuration class should be always registered as a service:
```yaml
services:
    App\Model\ImportConfiguration\UserImportConfiguration: ~
```

Controller
--------------

* Entity Manager is no longer used in controller methods.
* If you use custom controller, to make sure that configuration class will be injected automatically:
  * Interface `JG\BatchEntityImportBundle\Controller\ImportConfigurationAutoInjectInterface` should be implemented.
  * Trait `JG\SonataBatchEntityImportBundle\Controller\ImportConfigurationAutoInjectTrait` should be used to add needed methods.
  * Use method `getImportConfiguration()` from default controller.
