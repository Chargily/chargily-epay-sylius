<?php

declare(strict_types=1);

namespace Kiakaha\ChargilyPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        //$treeBuilder = new TreeBuilder();
        //$rootNode = $treeBuilder->root('kiakaha_sylius_chargily_plugin');
        $treeBuilder = new TreeBuilder('kiakaha_sylius_chargily_plugin');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
