<?php

namespace Netinfluence\UploadBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('netinfluence_upload');

        $rootNode
            ->children()
                ->arrayNode('validation')
                    ->isRequired()
                    ->children()
                        ->arrayNode('image')
                            ->info('Validation rules for images')
                            ->prototype('array')
                                // Maybe we have some more content
                                ->prototype('variable')->end()
                            ->end()
                            ->defaultValue(array(
                                'NotNull'   => array(),
                                'Image'     => array(
                                    // This looks like a sensible default for most users
                                    'maxSize' => '10M',
                                    // We set some defaults corresponding to a standard GD install - else ImagineBundle will fail
                                    'mimeTypes' => array('image/gif', 'image/jpg', 'image/jpeg', 'image/png', 'image/bmp', 'image/x-windows-bmp')
                                )
                            ))
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('filesystems')
                    ->children()
                        ->scalarNode('sandbox')
                            ->isRequired()
                            ->cannotbeEmpty()
                            ->info('Name of the Gaufrette filesystem to use to store temporary files, such as "gaufrette.sandbox_filesystem"')
                        ->end()
                        ->scalarNode('final')
                            ->isRequired()
                            ->cannotbeEmpty()
                            ->info('Name of the Gaufrette filesystem to use to finally store files, such as "gaufrette.final_filesystem"')
                        ->end()
                    ->end()
                    ->isRequired()
                ->end()
                ->booleanNode('overwrite')
                    ->info('Whether to overwrite or not files in Final filesystem. True is advised, as you may have files sent twice.')
                    ->defaultValue(true)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
