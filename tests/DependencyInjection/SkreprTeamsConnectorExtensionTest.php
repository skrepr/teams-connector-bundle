<?php

declare(strict_types=1);

namespace DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Skrepr\TeamsConnector\Client;
use Skrepr\TeamsConnectorBundle\DependencyInjection\SkreprTeamsConnectorExtension;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class SkreprTeamsConnectorExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadWithNoConfiguration(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child config "endpoint" under "skrepr_teams_connector" must be configured: The Microsoft teams incoming webHooks URL.');

        $this->load();
    }

    public function testLoadWithConfiguration(): void
    {
        $endpoint = 'https://outlook.office.com/webhook/xxxxxxxx-xxxx-xxxxxxxx-xxxxxx-xxxxxx-xxx-xxxxxx/IncomingWebhook/xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

        $this->load([
            'endpoint' => $endpoint,
        ]);

        $this->assertContainerBuilderHasParameter('skrepr_teams_connector.endpoint', $endpoint);
        $this->assertContainerBuilderHasService('skrepr_teams_connector.client');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('skrepr_teams_connector.client', 0, '%skrepr_teams_connector.endpoint%');
        $this->assertContainerBuilderHasAlias(Client::class, 'skrepr_teams_connector.client');
    }

    /**
     * @inheritDoc
     */
    protected function getContainerExtensions(): array
    {
        return [
          new SkreprTeamsConnectorExtension(),
        ];
    }
}
