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

namespace Veslo\AnthillBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Veslo\AnthillBundle\Command\Roadmap\ListCommand;
use Veslo\AnthillBundle\Vacancy\AntQueen;
use Veslo\AnthillBundle\Vacancy\DiggerInterface;

/**
 * Represents dung (vacancies) digging process.
 * This is an initial stage of vacancy research workflow, where objects will be 'found' first time
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
     * @var DiggerInterface
     */
    private $digger;

    /**
     * DiggingCommand constructor.
     *
     * @param AntQueen        $antQueen Aggregates all available roadmap services for vacancy parsing
     * @param DiggerInterface $digger   Digs some dung (vacancies) from internet and sends to queue for processing
     * @param string|null     $name     The name of the command; passing null means it must be set in configure()
     */
    public function __construct(AntQueen $antQueen, DiggerInterface $digger, ?string $name = null)
    {
        $this->antQueen = $antQueen;
        $this->digger   = $digger;

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
                'Key of configuration record for roadmap, determines vacancy search criteria'
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
        $roadmapName      = (string) $input->getArgument('roadmap');
        $configurationKey = (string) $input->getArgument('configuration');
        $iterations       = (int) $input->getOption('iterations');

        $roadmap = $this->antQueen->buildRoadmap($roadmapName, $configurationKey);

        $successfulIterations = $this->digger->dig($roadmap, $iterations);

        $roadmapName     = $roadmap->getName();
        $messageComplete = str_replace(
            ['{roadmapName}', '{iterations}', '{successful}'],
            [$roadmapName, $iterations, $successfulIterations],
            'Digging complete for roadmap: {roadmapName} ({iterations} iterations, {successful} successful).'
        );
        $output->writeln($messageComplete);
    }
}
