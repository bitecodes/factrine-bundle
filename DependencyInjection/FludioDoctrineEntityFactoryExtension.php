<?php

namespace Fludio\DoctrineEntityFactoryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FludioDoctrineEntityFactoryExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $bundles = $container->getParameter('kernel.bundles');

        // directories
        $directories = [];
        if ($config['auto_detection']) {
            foreach ($bundles as $name => $class) {
                $ref = new \ReflectionClass($class);
                $directory = dirname($ref->getFileName()).'/Resources/config/entity-factory';
                if(file_exists($directory)) {
                    $directories[$ref->getNamespaceName()] = dirname($ref->getFileName()).'/Resources/config/entity-factory';
                }
            }
        }

        $container
            ->getDefinition('fludio_doctrine_entity_factory.metadata.config_loader')
            ->replaceArgument(0, $directories);

    }
}
