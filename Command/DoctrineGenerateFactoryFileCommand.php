<?php

namespace Fludio\DoctrineEntityFactoryBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $dumper = $this->getContainer()->get('yaml_dumper');


        $array = $this->getContainer()->get('fludio_factory.schema_reader')->read();

        $yaml = $dumper->dump($array, 100);

        file_put_contents('./entites.yml', $yaml);
    }
}