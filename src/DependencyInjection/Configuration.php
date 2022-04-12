<?php

declare(strict_types=1);

namespace Skrepr\TeamsConnectorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    private const TREE_BUILDER = 'skrepr_teams_connector';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::TREE_BUILDER);

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('endpoint')
                    ->isRequired()->cannotBeEmpty()
                    ->info('The Microsoft teams incoming webHooks URL.')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
