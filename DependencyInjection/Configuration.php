<?php

namespace Abacus\AdvanceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * The action service ID for ADvance API is taken from the pattern advance_api.{site}.{action_name}
 *
 * abacus_advance:
 *     api:
 *         {site}:
 *             actions:
 *                 {action_name}: service.id
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('abacus_advance');

        $rootNode
            ->children()
                ->arrayNode('api')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('actions')
                                ->children()
                                    ->scalarNode('categories')->end()
                                    ->scalarNode('story_category_mapping')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

