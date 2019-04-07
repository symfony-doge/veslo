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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Veslo\AnthillBundle\Dto\Vacancy\LocationDto;
use Veslo\AnthillBundle\Vacancy\Parser\Earwig;
use Veslo\AppBundle\Workflow\Vacancy\PitInterface;

/**
 * Represents dung (vacancies) parsing process
 * This is 'to_parse' transition of vacancy research workflow, moves an object place from 'found' to 'parsed'
 *
 * Usage example:
 * ```
 * bin/console veslo:anthill:parsing --iterations=10
 * ```
 */
class ParsingCommand extends Command
{
    /**
     * Vacancy URL storage
     * Should return instances of vacancy LocationDto or null, if storage is empty
     *
     * @var PitInterface
     *
     * @see LocationDto
     */
    private $source;

    /**
     * Parses vacancy URL from queue and sends data further to conveyor according to workflow
     *
     * @var Earwig
     */
    private $earwig;

    /**
     * ParsingCommand constructor.
     *
     * @param PitInterface $source Vacancy URL storage
     * @param Earwig       $earwig Parses vacancy URL from queue and sends data further according to workflow
     * @param string|null  $name   The name of the command; passing null means it must be set in configure()
     */
    public function __construct(PitInterface $source, Earwig $earwig, ?string $name = null)
    {
        $this->source = $source;
        $this->earwig = $earwig;

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

        $successfulIterations = $this->earwig->parse($this->source, $iterations);

        $messageComplete = str_replace(
            ['{iterations}', '{successful}'],
            [$iterations, $successfulIterations],
            'Parsing complete ({iterations} iterations, {successful} successful).'
        );
        $output->writeln($messageComplete);
    }
}
