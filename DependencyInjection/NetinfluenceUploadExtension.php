<?php

namespace Netinfluence\UploadBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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

        if (0 === count(array_filter($configs))) {
            throw new \Exception('You should add "netinfluence_upload" minimal config to "app/config/config.yml"');
        }

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('netinfluence_upload.validation.image_constraints', $config['validation']['image']);

        // Manipulations on filesystems

        $fileListener = $container->getDefinition('netinfluence_upload.file_listener');
        $fileListener->replaceArgument(0, new Reference($config['filesystems']['sandbox']));

        $fileListener = $container->getDefinition('netinfluence_upload.file_manager');
        $fileListener->replaceArgument(0, new Reference($config['filesystems']['sandbox']));
        $fileListener->replaceArgument(1, new Reference($config['filesystems']['final']));
    }
}
