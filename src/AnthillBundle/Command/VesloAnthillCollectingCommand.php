<?php

namespace Veslo\AnthillBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Represents dung (vacancies) collecting process.
 */
class VesloAnthillCollectingCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('veslo:anthill:collecting')
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
        $container = $this->getContainer();
        $antWorker = $container->get('veslo.anthill.vacancy.ant_worker');

        $iterations = $input->getOption('iterations');

        for ($iteration = 0; $iteration < $iterations; ++$iteration) {
            $antWorker->collect();
        }

        $output->writeln('Digging complete.');
    }
}
