<?php

namespace Fludio\DoctrineEntityFactoryBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Yaml\Parser;

class DoctrineGenerateFactoryFileCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('doctrine:generate:factory-file')
            ->setDescription('It generates the factory files from your schema.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parser = new Parser();
        $dumper = $this->getContainer()->get('yaml_dumper');

        $entityConfigs = $this->getContainer()->get('fludio_factory.schema_reader')->read();

        $kernel = $this->getContainer()->get('kernel');
        $bundles = $kernel->getBundles();

        /** @var Bundle $bundle */
        foreach($bundles as $bundle) {
            if(strpos($bundle->getPath(), '/src/') === false) {
                continue;
            }
            $factoryDir = $bundle->getPath() . '/Resources/config/entity-factory';
            $bundleNamespace = $bundle->getNamespace();

            foreach($entityConfigs as $entity => $config) {
                $refl = new \ReflectionClass($entity);
                if(strpos($refl->getNamespaceName(), $bundleNamespace) !== false) {
                    $path = $factoryDir . DIRECTORY_SEPARATOR . $refl->getShortName() . '.yml';

                    if(!file_exists($factoryDir)) {
                        mkdir($factoryDir, 0777, true);
                    }

                    if(file_exists($path)) {
                        $oldYaml = file_get_contents($path);
                        $oldConfig = $parser->parse($oldYaml);
                        if($oldConfig) {
                            $config = array_replace_recursive($config, $oldConfig[$entity]);
                        }
                    }

                    $yaml = $dumper->dump([$entity => $config], 100);

                    file_put_contents($path, $yaml);
                }
            }
        }
    }
}