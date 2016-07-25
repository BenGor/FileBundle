<?php

/*
 * This file is part of the BenGorFile package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGorFile\FileBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * BenGor file bundle configuration class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('ben_gor_file')
            ->children()
                ->arrayNode('file_class')->requiresAtLeastOneElement()
                ->prototype('array')
                    ->children()
                        ->scalarNode('class')
                            ->isRequired(true)
                        ->end()
                        ->scalarNode('persistence')
                            ->defaultValue('doctrine_orm')
                            ->validate()
                            ->ifNotInArray(['doctrine_orm'])
                                ->thenInvalid('Invalid persistence layer "%s"')
                            ->end()
                        ->end()
                        ->scalarNode('storage')
                            ->defaultValue('symfony')
                            ->validate()
                            ->ifNotInArray(['gaufrette', 'symfony'])
                                ->thenInvalid('Invalid storage implementation "%s"')
                            ->end()
                        ->end()
                        ->scalarNode('upload_destination')
                            ->defaultValue('%kernel.root_dir%/../web')
                        ->end()
                        ->scalarNode('upload_dir')
                            ->defaultValue(null)
                        ->end()
                        ->scalarNode('data_transformer')
                            ->defaultValue('BenGorFile\\File\\Application\\DataTransformer\\FileDTODataTransformer')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
