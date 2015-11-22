<?php

namespace Fludio\FactrineBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DoctrineSeedEntityCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this
            ->setName('factrine:seed:entity')
            ->setDescription('Seed an entity')
            ->addArgument(
                'source',
                InputArgument::REQUIRED,
                'Which entity should be seeded?'
            )
            ->addOption(
                'times',
                't',
                InputOption::VALUE_OPTIONAL,
                'How many entities should be generated?',
                1
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $factory = $this->getContainer()->get('fludio_factory.factory');
        $entity = $input->getArgument('source');
        $times = $input->getOption('times');

        $factory
            ->times($times)
            ->create($entity);

        $output->writeln(sprintf('%s seeded.', $entity));
    }
}
