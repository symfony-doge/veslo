<?php

namespace Veslo\AnthillBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Veslo\AnthillBundle\Vacancy\DungBeetle;
use Veslo\AnthillBundle\Vacancy\RoadmapStorage;

/**
 * Represents dung (vacancies) digging process.
 */
class DiggingCommand extends Command
{
    /**
     * Aggregates all available roadmap services for vacancy parsing
     *
     * @var RoadmapStorage
     */
    private $roadmapStorage;

    /**
     * Digs some dung (vacancies) from internet and sends to queue for processing
     *
     * @var DungBeetle
     */
    private $dungBeetle;

    /**
     * DiggingCommand constructor.
     *
     * @param RoadmapStorage $roadmapStorage Aggregates all available roadmap services for vacancy parsing
     * @param DungBeetle     $dungBeetle     Digs some dung (vacancies) from internet and sends to queue for processing
     * @param string|null    $name           The name of the command; passing null means it must be set in configure()
     */
    public function __construct(RoadmapStorage $roadmapStorage, DungBeetle $dungBeetle, ?string $name = null)
    {
        $this->roadmapStorage = $roadmapStorage;
        $this->dungBeetle     = $dungBeetle;

        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Digs some dung (vacancies) from internet and sends to queue for processing')
            ->addArgument(
                'roadmapName',
                InputArgument::REQUIRED,
                'Digging plan during which vacancies will be parsed from specified source'
            )
            ->addArgument(
                'query',
                InputArgument::REQUIRED,
                'Digging criteria by which vacancies will be selected for parsing'
            )
            ->addOption(
                'iterations',
                'i',
                InputOption::VALUE_REQUIRED,
                'Maximum count of vacancies from internet to proceed during command\'s single run',
                10
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $roadmapName = $input->getArgument('roadmapName');
        //$query       = $input->getArgument('query');
        $iterations  = $input->getOption('iterations');

        $roadmap = $this->roadmapStorage->require($roadmapName);

        $this->dungBeetle->dig($roadmap, $iterations);

        $messageComplete = str_replace(
            ['{roadmapName}', '{iterations}'],
            [$roadmapName, $iterations],
            'Digging complete for roadmap: {roadmapName} ({iterations} iterations).'
        );
        $output->writeln($messageComplete);
    }
}
