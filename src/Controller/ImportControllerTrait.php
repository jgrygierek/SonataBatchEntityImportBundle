<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Controller;

use JG\BatchEntityImportBundle\Controller\BaseImportControllerTrait;
use JG\BatchEntityImportBundle\Form\Type\MatrixType;
use JG\BatchEntityImportBundle\Model\Matrix\Matrix;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

trait ImportControllerTrait
{
    use BaseImportControllerTrait;

    public function importAction(Request $request): Response
    {
        /** @var ValidatorInterface $validator */
        $validator = $this->get('validator');

        return $this->doImport($request, $validator, $this->getDoctrine()->getManager());
    }

    public function importSaveAction(Request $request): Response
    {
        /** @var TranslatorInterface $translator */
        $translator = $this->get('translator');

        return $this->doImportSave($request, $translator);
    }

    protected function redirectToImport(): RedirectResponse
    {
        return new RedirectResponse($this->admin->generateUrl('import'));
    }

    protected function getImportConfigurationClassName(): string
    {
        return $this->admin->getImportConfigurationClassName();
    }

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
        $importConfiguration = $this->getImportConfiguration();

        return $this->createForm(
            MatrixType::class,
            $matrix,
            [
                'configuration' => $importConfiguration,
                'constraints' => $importConfiguration->getMatrixConstraints(),
            ]
        );
    }
}
