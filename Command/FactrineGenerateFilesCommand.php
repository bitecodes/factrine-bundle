<?php

namespace Fludio\FactrineBundle\Command;

use Fludio\FactrineBundle\Factory\ConfigProvider\ConfigGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Dumper;

class FactrineGenerateFilesCommand extends Command
{
    /**
     * @var ConfigGenerator
     */
    private $generator;
    /**
     * @var Kernel
     */
    private $kernel;

    public function __construct(ConfigGenerator $generator, Kernel $kernel)
    {
        parent::__construct();

        $this->generator = $generator;
        $this->kernel = $kernel;
    }

    protected function configure()
    {
        $this
            ->setName('factrine:generate:files')
            ->setDescription('It generates the factory files for your entities.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dumper = new Dumper();

        $bundles = $this->kernel->getBundles();
        $configs = $this->generator->generate();

        /** @var Bundle $bundle */
        foreach ($bundles as $bundle) {
            foreach ($configs as $entity => $config) {
                if (strpos($entity, $bundle->getNamespace()) === false) {
                    continue;
                }

                $refl = new \ReflectionClass($entity);

                $factoryDir = $this->getDirectory($bundle);
                $file = $factoryDir . $refl->getShortName() . '.yml';

                if (!file_exists($factoryDir)) {
                    mkdir($factoryDir, 0777, true);
                }

                $content = $dumper->dump([$entity => $config], 4);

                // TODO check for existing files and merge them
                file_put_contents($file, $content);
            }
        }
    }

    /**
     * @param $bundle
     * @return string
     */
    protected function getDirectory($bundle)
    {
        $path = $bundle->getPath();
        $factoryDir = $path . '/Resources/config/factrine/';
        return $factoryDir;
    }
}
