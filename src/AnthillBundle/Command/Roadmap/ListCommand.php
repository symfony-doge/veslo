<?php

namespace Veslo\AnthillBundle\Command\Roadmap;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Veslo\AnthillBundle\Vacancy\AntQueen;

/**
 * Shows all available roadmaps for dung (vacancies) digging
 *
 * Usage example:
 * ```
 * bin/console veslo:anthill:roadmap:list
 * ```
 */
class ListCommand extends Command
{
    /**
     * Aggregates all available roadmap services for vacancy parsing
     *
     * @var AntQueen
     */
    private $antQueen;

    /**
     * ListCommand constructor.
     *
     * @param AntQueen    $antQueen Aggregates all available roadmap services for vacancy parsing
     * @param string|null $name     The name of the command; passing null means it must be set in configure()
     */
    public function __construct(AntQueen $antQueen, ?string $name = null)
    {
        $this->antQueen = $antQueen;

        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setDescription('Shows all available roadmaps for dung (vacancies) digging');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $roadmaps = $this->antQueen->getRoadmaps();
        $message  = '';

        foreach ($roadmaps as $roadmapName => $roadmap) {
            $message .= "\n  <info>$roadmapName</info>\n";
            $message .= '    Type: ' . get_class($roadmap) . "\n";
        }

        $message .= "\nNew roadmaps can be registered in <comment>roadmaps.yml</comment>";

        $output->writeln($message);
    }
}
