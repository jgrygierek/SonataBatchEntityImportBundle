<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
        $validator = $this->container->get('validator');

        return $this->doImport($request, $validator, $this->container->get('doctrine')->getManager());
    }

    public function importSaveAction(Request $request): Response
    {
        /** @var TranslatorInterface $translator */
        $translator = $this->container->get('translator');

        return $this->doImportSave($request, $translator, $this->container->get('doctrine')->getManager());
    }

    public static function getSubscribedServices(): array
    {
        $newServices = [
            'validator' => ValidatorInterface::class,
            'doctrine' => ManagerRegistry::class,
        ];

        return array_merge($newServices, parent::getSubscribedServices());
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

    protected function createMatrixForm(Matrix $matrix, EntityManagerInterface $entityManager): FormInterface
    {
        return $this->createForm(
            MatrixType::class,
            $matrix,
            [
                'configuration' => $this->getImportConfiguration($entityManager),
            ]
        );
    }
}
