<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use InvalidArgumentException;
use JG\BatchEntityImportBundle\BatchEntityImportBundle;
use JG\SonataBatchEntityImportBundle\SonataBatchEntityImportBundle;
use Knp\Bundle\MenuBundle\KnpMenuBundle;
use ReflectionClass;
use Sonata\AdminBundle\SonataAdminBundle;
use Sonata\BlockBundle\SonataBlockBundle;
use Sonata\Doctrine\Bridge\Symfony\SonataDoctrineBundle;
use Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle;
use Sonata\Form\Bridge\Symfony\SonataFormBundle;
use Sonata\Twig\Bridge\Symfony\SonataTwigBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\UX\StimulusBundle\StimulusBundle;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): array
    {
        $bundles = [
            new SonataAdminBundle(),
            new SonataBlockBundle(),
            new SonataDoctrineORMAdminBundle(),
            new DoctrineBundle(),
            new KnpMenuBundle(),
            new FrameworkBundle(),
            new SecurityBundle(),
            new TwigBundle(),
            new BatchEntityImportBundle(),
            new SonataBatchEntityImportBundle(),
            new SonataDoctrineBundle(),
            new SonataTwigBundle(),
            new SonataFormBundle(),
        ];

        if (\class_exists(StimulusBundle::class)) {
            $bundles[] = new StimulusBundle();
        }

        return $bundles;
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        try {
            $this->locateResource('@SonataAdminBundle/Resources/config/routing/sonata_admin.php');
            $routes->import(__DIR__ . '/config/routes.yaml');
        } catch (InvalidArgumentException) {
            $routes->import(__DIR__ . '/config/routes_old.yaml');
        }
    }

    protected function configureContainer(ContainerConfigurator $container, LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/config.yaml');

        $reflectionClass = new ReflectionClass(SecurityExtension::class);
        if ($reflectionClass->hasProperty('authenticatorManagerEnabled')) {
            $container->extension('security', [
                'enable_authenticator_manager' => true,
            ]);
        }

        if (self::MAJOR_VERSION >= 7) {
            $container->extension('framework', [
                'validation' => ['enable_attributes' => true],
            ]);

            if (\PHP_VERSION_ID >= 80400) {
                $container->extension('doctrine', [
                    'orm' => ['enable_native_lazy_objects' => true],
                ]);
            }
        }
    }
}
