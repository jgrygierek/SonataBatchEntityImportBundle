<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
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

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): array
    {
        return [
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
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(__DIR__ . '/config/routes.yaml');
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
    }
}
