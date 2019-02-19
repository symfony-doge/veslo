<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Veslo\AnthillBundle\Vacancy\Parser\Earwig;
use Veslo\AppBundle\Workflow\Vacancy\PitInterface;

/**
 * Represents vacancy parsing process
 *
 * Usage example:
 * ```
 * bin/console veslo:anthill:parsing --iterations=10
 * ```
 */
class ParsingCommand extends Command
{
    /**
     * Parses vacancy URL from queue and sends data further to conveyor according to workflow
     *
     * @var Earwig
     */
    private $earwig;

    /**
     * Vacancy URL storage
     *
     * @var PitInterface
     */
    private $dungPit;

    /**
     * ParsingCommand constructor.
     *
     * @param Earwig       $earwig  Parses vacancy URL from queue and sends data further according to workflow
     * @param PitInterface $dungPit Vacancy URL storage
     * @param string|null  $name    The name of the command; passing null means it must be set in configure()
     */
    public function __construct(Earwig $earwig, PitInterface $dungPit, ?string $name = null)
    {
        $this->earwig  = $earwig;
        $this->dungPit = $dungPit;

        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Parses vacancy URL and sends result data to queue for processing')
            ->addOption(
                'iterations',
                'i',
                InputOption::VALUE_REQUIRED,
                'Maximum count of vacancies from parsing queue to proceed during command\'s single run',
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

        $successfulIterations = $this->earwig->parse($this->dungPit, $iterations);

        $messageComplete = str_replace(
            ['{iterations}', '{successful}'],
            [$iterations, $successfulIterations],
            'Parsing complete ({iterations} iterations, {successful} successful).'
        );
        $output->writeln($messageComplete);
    }
}
