<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Controller;

use JG\SonataBatchEntityImportBundle\Admin\AdminWithImportInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @property AdminInterface|AdminWithImportInterface $admin
 */
class ImportCrudController extends CRUDController
{
    use ImportControllerTrait;

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

        return $this->doImportSave($request, $translator, $this->getDoctrine()->getManager());
    }

    protected function redirectToImport(): RedirectResponse
    {
        return new RedirectResponse($this->admin->generateUrl('import'));
    }

    protected function getImportConfigurationClassName(): string
    {
        return $this->admin->getImportConfigurationClassName();
    }

    public static function getSubscribedServices(): array
    {
        return ['validator' => ValidatorInterface::class] + parent::getSubscribedServices();
    }
}
