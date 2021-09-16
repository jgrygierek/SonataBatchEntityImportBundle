<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Controller;

use JG\SonataBatchEntityImportBundle\Admin\AdminWithImportInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property AdminInterface|AdminWithImportInterface $admin
 */
class ImportCrudController extends CRUDController
{
    use ImportControllerTrait;

    public function importAction(Request $request): Response
    {
        return $this->doImport($request, $this->container->get('validator'), $this->getDoctrine()->getManager());
    }

    public function importSaveAction(Request $request): Response
    {
        return $this->doImportSave($request, $this->get('translator'), $this->getDoctrine()->getManager());
    }

    protected function redirectToImport(): RedirectResponse
    {
        return new RedirectResponse($this->admin->generateUrl('import'));
    }

    protected function getImportConfigurationClassName(): string
    {
        return $this->admin->getImportConfigurationClassName();
    }
}
