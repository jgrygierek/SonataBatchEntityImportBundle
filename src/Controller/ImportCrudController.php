<?php

namespace JG\SonataBatchEntityImportBundle\Controller;

use JG\BatchEntityImportBundle\Controller\ImportControllerInterface;
use JG\BatchEntityImportBundle\Model\Matrix;
use PhpOffice\PhpSpreadsheet\Exception;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property AdminInterface|ImportControllerInterface $admin
 */
class ImportCrudController extends CRUDController implements ImportControllerInterface
{
    use ImportControllerTrait;

    public function importAction(Request $request): Response
    {
        return $this->doImport($request);
    }

    public function importSaveAction(Request $request): Response
    {
        return $this->doImportSave($request);
    }

    private function redirectToImport(): RedirectResponse
    {
        return new RedirectResponse($this->admin->generateUrl('import'));
    }

    private function getMatrixSaveActionUrl(): string
    {
        return 'asas';
    }

    private function getImportConfigurationClassName(): string
    {
        return $this->admin->getImportConfigurationClassName();
    }
}
