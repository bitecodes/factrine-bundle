<?php

namespace Fludio\FactrineBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Yaml\Dumper;

class DoctrineGenerateFactoryFileCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('factrine:generate:files')
            ->setDescription('It generates the factory files for your entities.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dumper = new Dumper();
        $kernel = $this->getContainer()->get('kernel');
        $generator = $this->getContainer()->get('fludio_factory.config_provider.config_generator');

        $bundles = $kernel->getBundles();
        $configs = $generator->generate();

        /** @var Bundle $bundle */
        foreach($bundles as $bundle) {
            foreach($configs as $entity => $config) {
                if(strpos($entity, $bundle->getNamespace()) === false) {
                    continue;
                }

                $refl = new \ReflectionClass($entity);

                $path = $bundle->getPath();
                $factoryDir = $path . '/Resources/config/factrine/';
                $file = $factoryDir . $refl->getShortName() . '.yml';

                if(!file_exists($factoryDir)) {
                    mkdir($factoryDir, 0777, true);
                }

                $content = $dumper->dump([$entity => $config], 4);

                // TODO check for existing files and merge them
                file_put_contents($file, $content);
            }
        }
    }
}
