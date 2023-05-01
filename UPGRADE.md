UPGRADE TO 1.6.x
=======================

CSV File
--------------
* Now CSV file can contain spaces and dashes as a header name, for example "my column name" or "my-column-name".

Import Configuration class
--------------
* When header name contains spaces we should use underscores instead of spaces when defining fields names in fields definitions and in constraints.

UPGRADE TO 1.5.x
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

