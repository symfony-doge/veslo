<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

declare(strict_types=1);

namespace Veslo\SanityBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Veslo\AnthillBundle\Dto\Vacancy\Collector\AcceptanceDto;
use Veslo\AppBundle\Workflow\Vacancy\PitInterface;
use Veslo\SanityBundle\Vacancy\Indexer\Cockroach;

/**
 * Represents dung (vacancies) indexing process.
 * This is 'to_index' transition of vacancy research workflow, from 'collected' to 'indexed'
 *
 * Usage example:
 * ```
 * bin/console veslo:sanity:indexing --iterations=10
 * ```
 *
 * @see https://en.wikipedia.org/wiki/Ministries_of_Nineteen_Eighty-Four#Ministry_of_Truth
 */
class IndexingCommand extends Command
{
    /**
     * Collected vacancy data storage
     * Provides AcceptanceDto instances for indexing or null if storage is empty
     *
     * @var PitInterface
     *
     * @see AcceptanceDto
     */
    private $source;

    /**
     * Delivers a vacancy to the Ministry of Truth for analysis and sanity index calculation
     *
     * @var Cockroach
     */
    private $indexer;

    /**
     * CollectingCommand constructor.
     *
     * @param PitInterface $source  Collected vacancy data storage
     * @param Cockroach    $indexer Delivers a vacancy to the Ministry of Truth for sanity index calculation
     * @param string|null  $name    The name of the command; passing null means it must be set in configure()
     */
    public function __construct(PitInterface $source, Cockroach $indexer, ?string $name = null)
    {
        $this->source  = $source;
        $this->indexer = $indexer;

        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Calculates a sanity index for vacancies via "Ministry of Truth" API')
            ->addOption(
                'iterations',
                'i',
                InputOption::VALUE_REQUIRED,
                'Maximum count of vacancies to proceed for a single run',
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

        $successfulIterations = $this->indexer->deliver($this->source, $iterations);

        $messageComplete = str_replace(
            ['{iterations}', '{successful}'],
            [$iterations, $successfulIterations],
            'Indexing complete ({iterations} iterations, {successful} successful).'
        );
        $output->writeln($messageComplete);
    }
}
