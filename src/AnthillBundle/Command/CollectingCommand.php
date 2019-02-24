<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;
use Veslo\AnthillBundle\Vacancy\Collector\AntWorker;
use Veslo\AppBundle\Workflow\Vacancy\PitInterface;

/**
 * Represents dung (vacancies) collecting process.
 * This is 'to_collect' transition of vacancy research workflow, from 'parsed' to 'collected'
 *
 * Usage example:
 * ```
 * bin/console veslo:anthill:collecting --iterations=10
 * ```
 */
class CollectingCommand extends Command
{
    /**
     * Vacancy parsed data storage
     * Should return ParsedDto instances or null if storage is empty
     *
     * @var PitInterface
     *
     * @see ParsedDto
     */
    private $source;

    /**
     * Collects raw vacancy data from queue and transforms it to the local persistent entities
     *
     * @var AntWorker
     */
    private $antWorker;

    /**
     * CollectingCommand constructor.
     *
     * @param PitInterface $source    Vacancy parsed data storage
     * @param AntWorker    $antWorker Collects raw vacancy data from queue and transforms it to the local entities
     * @param string|null  $name      The name of the command; passing null means it must be set in configure()
     */
    public function __construct(PitInterface $source, AntWorker $antWorker, ?string $name = null)
    {
        $this->source    = $source;
        $this->antWorker = $antWorker;

        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Collects dung (vacancies) from queue and sends to the Ministry of Truth for analysis')
            ->addOption(
                'iterations',
                null,
                InputOption::VALUE_REQUIRED,
                'Maximum count of vacancy data entries to proceed during command\'s single run',
                10
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $iterations = (int) $input->getOption('iterations');

        $successfulIterations = $this->antWorker->collect($this->source, $iterations);

        $messageComplete = str_replace(
            ['{iterations}', '{successful}', '{memory}', '{memoryPeak}'],
            [$iterations, $successfulIterations, memory_get_usage(), memory_get_peak_usage()],
            'Collecting complete ({iterations} iterations, {successful} successful).'
            . ' Memory usage: {memory}/{memoryPeak}'
        );
        $output->writeln($messageComplete);
    }
}