<?php

namespace JG\SonataBatchEntityImportBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use JG\BatchEntityImportBundle\BatchEntityImportBundle;
use JG\SonataBatchEntityImportBundle\SonataBatchEntityImportBundle;
use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Sonata\AdminBundle\SonataAdminBundle;
use Sonata\BlockBundle\SonataBlockBundle;
use Sonata\CoreBundle\SonataCoreBundle;
use Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    private array $configs = [];

    public function registerBundles(): array
    {
        return [
            new BatchEntityImportBundle(),
            new SonataBatchEntityImportBundle(),
            new SonataAdminBundle(),
            new SonataCoreBundle(),
            new SonataBlockBundle(),
            new SonataDoctrineORMAdminBundle(),
            new DoctrineBundle(),
            new KnpMenuBundle(),
            new FrameworkBundle(),
            new SecurityBundle(),
            new TwigBundle(),
        ];
    }

    public function setConfigs(array $configs): void
    {
        $this->configs = $configs;
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $routes->import(__DIR__ . '/config/routes.yaml');
    }

    protected function configureContainer(ContainerBuilder $containerBuilder, LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/config.yaml');

        foreach ($this->configs as $config) {
            $loader->load($config);
        }
    }
}
