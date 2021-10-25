UPGRADE TO 1.4.x
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

UPGRADE TO 1.3.x
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
