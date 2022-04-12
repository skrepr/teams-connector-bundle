<?php

declare(strict_types=1);

namespace DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Skrepr\TeamsConnectorBundle\DependencyInjection\Configuration;
use Skrepr\TeamsConnectorBundle\DependencyInjection\SkreprTeamsConnectorExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    public function testMinimalConfigurationProcess(): void
    {
        $expectedConfiguration = [
            'endpoint' => 'https://outlook.office.com/webhook/xxxxxxxx-xxxx-xxxxxxxx-xxxxxx-xxxxxx-xxx-xxxxxx/IncomingWebhook/xxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        ];

        $sources = [
            __DIR__.'/../Fixtures/config/config.yml',
        ];

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }

    /**
     * @inheritDoc
     */
    protected function getContainerExtension(): ExtensionInterface
    {
        return new SkreprTeamsConnectorExtension();
    }

    /**
     * @inheritDoc
     */
    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }
}
