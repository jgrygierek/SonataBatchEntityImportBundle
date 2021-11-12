<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use JG\BatchEntityImportBundle\BatchEntityImportBundle;
use JG\SonataBatchEntityImportBundle\SonataBatchEntityImportBundle;
use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Sonata\AdminBundle\SonataAdminBundle;
use Sonata\BlockBundle\SonataBlockBundle;
use Sonata\CoreBundle\SonataCoreBundle;
use Sonata\Doctrine\Bridge\Symfony\Bundle\SonataDoctrineBundle as OldSonataDoctrineBundle;
use Sonata\Doctrine\Bridge\Symfony\SonataDoctrineBundle;
use Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle;
use Sonata\Twig\Bridge\Symfony\SonataTwigBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\RouteCollectionBuilder;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    private array $configs = [];

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
        ];

        if (class_exists(SonataDoctrineBundle::class)) {
            $bundles[] = new SonataDoctrineBundle();
        } elseif (class_exists(OldSonataDoctrineBundle::class)) {
            $bundles[] = new OldSonataDoctrineBundle();
        }

        if (class_exists(SonataCoreBundle::class)) {
            $bundles[] = new SonataCoreBundle();
        }

        if (class_exists(SonataTwigBundle::class)) {
            $bundles[] = new SonataTwigBundle();
        }

        return $bundles;
    }

    /**
     * @param RoutingConfigurator|RouteCollectionBuilder $routes
     */
    protected function configureRoutes($routes): void
    {
        $routes->import(__DIR__ . '/config/routes.yaml');
    }

    protected function configureContainer(ContainerBuilder $containerBuilder, LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/config.yaml');

        foreach ($this->configs as $config) {
            $loader->load($config);
        }

        $containerBuilder->loadFromExtension('framework', [
            'session' => property_exists(WebTestCase::class, 'container')
                ? ['storage_id' => 'session.storage.mock_file']
                : ['storage_factory_id' => 'session.storage.factory.mock_file'],
        ]);
    }
}
