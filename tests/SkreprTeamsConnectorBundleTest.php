<?php

declare(strict_types=1);


use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractContainerBuilderTestCase;
use Skrepr\TeamsConnectorBundle\SkreprTeamsConnectorBundle;

class SkreprTeamsConnectorBundleTest extends AbstractContainerBuilderTestCase
{
    protected SkreprTeamsConnectorBundle $bundle;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bundle = new SkreprTeamsConnectorBundle();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testBuild(): void
    {
        $this->bundle->build($this->container);
    }
}
