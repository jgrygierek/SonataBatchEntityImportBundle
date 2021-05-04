<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Controller;

use JG\BatchEntityImportBundle\Controller\BaseImportControllerTrait;
use JG\BatchEntityImportBundle\Form\Type\MatrixType;
use JG\BatchEntityImportBundle\Model\Matrix\Matrix;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

trait ImportControllerTrait
{
    use BaseImportControllerTrait;

    protected function prepareView(string $view, array $parameters = []): Response
    {
        $parameters['action'] = 'import';

        return $this->renderWithExtraParams($view, $parameters);
    }

    protected function getSelectFileTemplateName(): string
    {
        return $this->getParameter('sonata_batch_entity_import.templates.select_file');
    }

    protected function getMatrixEditTemplateName(): string
    {
        return $this->getParameter('sonata_batch_entity_import.templates.edit_matrix');
    }

    protected function createMatrixForm(Matrix $matrix): FormInterface
    {
        return $this->createForm(
            MatrixType::class,
            $matrix,
            [
                'configuration' => $this->getImportConfiguration(),
            ]
        );
    }
}
