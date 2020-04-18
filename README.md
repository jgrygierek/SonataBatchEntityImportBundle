BatchImporterBundle
=

Bundle adds feature of batch inserting of data provided from different files. 
Data can be viewed and edited before saving to database.

Supported extensions:
* CSV
* XLS
* XLSX
* ODS


Form configuration
--

You have to create configuration class. You can add here definitions for dynamic fields loaded from file. 
Field name is the same as column name.
If no definition for field will be provided, `TextType` class will be used as default.

####Prepare your controller:
```php

use JG\SonataBatchEntityImportBundle\Controller\BaseImportTrait;
use JG\SonataBatchEntityImportBundle\Model\Matrix;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImportController extends AbstractController
{
    use BaseImportTrait;
}
```

###Prepare form configuration:
```php
namespace App\Form\Configuration;

use App\Entity\User;
use JG\SonataBatchEntityImportBundle\Model\Form\FormConfigurationInterface;
use JG\SonataBatchEntityImportBundle\Model\Form\FormFieldDefinition;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserImportFormConfiguration implements FormConfigurationInterface
{
    public function getEntityClassName(): string
    {
        return User::class;
    }

    public function getFieldsDefinitions(): array
    {
        return [
            'age'         => new FormFieldDefinition(
                'name', IntegerType::class, [
                    'attr' => [
                        'min' => 0,
                        'max' => 999,
                    ],
                ]
            ),
            'name'        => new FormFieldDefinition('name', TextType::class),
            'description' => new FormFieldDefinition(
                'description', TextareaType::class,
                [
                    'attr' => [
                        'rows' => 2,
                    ],
                ]
            ),
        ];
    }
}
```
