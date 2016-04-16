<?php

namespace BiteCodes\FactrineBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use BiteCodes\FactrineBundle\Factory\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class FactrineSeedEntityCommand extends Command
{
    const QUESTION_ENTITY_TO_SEED = 'Which entity should be seeded?';
    const QUESTION_ENTITY_COUNT = 'How many entities should be generated?';
    /**
     * @var Factory
     */
    private $factory;
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(Factory $factory, EntityManager $em)
    {
        parent::__construct();
        $this->factory = $factory;

        $this->em = $em;
    }

    protected function configure() {
        $this
            ->setName('factrine:seed:entity')
            ->setDescription('Seed an entity')
            ->addArgument(
                'entity',
                InputArgument::OPTIONAL,
                self::QUESTION_ENTITY_TO_SEED
            )
            ->addOption(
                'times',
                't',
                InputOption::VALUE_OPTIONAL,
                self::QUESTION_ENTITY_COUNT,
                1
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $entity = $input->getArgument('entity');
        $times = $input->getOption('times');

        /** @var SymfonyQuestionHelper $question */
        $question = $this->getHelper('question');
        if(!$entity) {
            $entity = $this->getEntity($input, $output, $question);
            $times = $this->getTimes($input, $output, $question);
        }

        $this->factory
            ->times($times)
            ->create($entity);

        $output->writeln(sprintf('%s seeded.', $entity));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $questionHelper
     * @return string
     */
    protected function getEntity(InputInterface $input, OutputInterface $output, QuestionHelper $questionHelper)
    {
        $choices = $this->getManagedEntities();

        return $questionHelper->ask($input, $output, new ChoiceQuestion(self::QUESTION_ENTITY_TO_SEED, $choices));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $questionHelper
     * @return string
     */
    private function getTimes(InputInterface $input, OutputInterface $output, QuestionHelper $questionHelper)
    {
        return $questionHelper->ask($input, $output, new Question(self::QUESTION_ENTITY_COUNT, 1));
    }

    /**
     * @return ChoiceQuestion
     */
    protected function getManagedEntities()
    {
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $entities = [];

        /** @var ClassMetadata $meta */
        foreach($metadata as $index => $meta) {
            $entities[$index] = $meta->getReflectionClass()->getName();
        }

        return $entities;
    }
}
