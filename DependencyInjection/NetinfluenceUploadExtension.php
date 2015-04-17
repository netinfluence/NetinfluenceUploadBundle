<?php

namespace Netinfluence\UploadBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class NetinfluenceUploadExtension
 */
class NetinfluenceUploadExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Register services
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('netinfluence_upload.validation.image_constraints', $config['validation.image']);

        // Manipulations on filesystems
        $fileListener = $container->getDefinition('netinfluence_upload.file_listener');
        $fileListener->replaceArgument(0, new Reference($config['netinfluence_upload.filesystems.sandbox']));
    }
}