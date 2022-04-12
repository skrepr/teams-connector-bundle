<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Skrepr\TeamsConnector\Client;
use Skrepr\TeamsConnectorBundle\SkreprTeamsConnectorBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class IntegrationTest extends TestCase
{
    public function testServiceIntegration(): void
    {
        $kernel = new SkreprTeamsConnectorIntegrationTestKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        // sanity check on service wiring
        $client = $container->get('skrepr_teams_connector.client');
        $this->assertInstanceOf(Client::class, $client);
    }
}

class SkreprTeamsConnectorIntegrationTestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new SkreprTeamsConnectorBundle(),
        ];
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/cache' . spl_object_hash($this);
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/logs' . spl_object_hash($this);
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->loadFromExtension('framework', [
            'secret' => 'foo',
        ]);

        $container->loadFromExtension('skrepr_teams_connector', [
            'endpoint' => 'http://localhost.com',
        ]);
    }

    protected function configureRouting(RoutingConfigurator $routes): void
    {
    }
}
