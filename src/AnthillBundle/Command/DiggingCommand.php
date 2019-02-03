<?php

namespace Veslo\AnthillBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Veslo\AnthillBundle\Command\Roadmap\ListCommand;
use Veslo\AnthillBundle\Vacancy\DungBeetle;
use Veslo\AnthillBundle\Vacancy\AntQueen;

/**
 * Represents dung (vacancies) digging process.
 *
 * Usage example:
 * ```
 * bin/console veslo:anthill:digging hh php --iterations=10
 * ```
 *
 * @see ListCommand
 */
class DiggingCommand extends Command
{
    /**
     * Aggregates all available roadmap services for vacancy parsing
     *
     * @var AntQueen
     */
    private $antQueen;

    /**
     * Digs some dung (vacancies) from internet and sends to queue for processing
     *
     * @var DungBeetle
     */
    private $dungBeetle;

    /**
     * DiggingCommand constructor.
     *
     * @param AntQueen    $antQueen   Aggregates all available roadmap services for vacancy parsing
     * @param DungBeetle  $dungBeetle Digs some dung (vacancies) from internet and sends to queue for processing
     * @param string|null $name       The name of the command; passing null means it must be set in configure()
     */
    public function __construct(AntQueen $antQueen, DungBeetle $dungBeetle, ?string $name = null)
    {
        $this->antQueen   = $antQueen;
        $this->dungBeetle = $dungBeetle;

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
                'roadmap',
                InputArgument::REQUIRED,
                'Name of digging plan during which vacancies will be parsed from specified source'
            )
            ->addArgument(
                'configuration',
                InputArgument::OPTIONAL,
                'Key of configuration record for roadmap, determines vacancy searching criteria'
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
        $roadmapName      = $input->getArgument('roadmap');
        $configurationKey = $input->getArgument('configuration');
        $iterations       = $input->getOption('iterations');

        $roadmap = $this->antQueen->requireRoadmap($roadmapName, $configurationKey);

        $this->dungBeetle->dig($roadmap, $iterations);

        $messageComplete = str_replace(
            ['{roadmapName}', '{iterations}'],
            [$roadmapName, $iterations],
            'Digging complete for roadmap: {roadmapName} ({iterations} iterations).'
        );
        $output->writeln($messageComplete);
    }
}
