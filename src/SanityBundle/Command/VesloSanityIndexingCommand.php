<?php

namespace Veslo\SanityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Represents dung (vacancies) indexing process.
 *
 * This process includes requests to the API (gently called "Ministry Of Truth",
 * see https://en.wikipedia.org/wiki/Ministries_of_Nineteen_Eighty-Four#Ministry_of_Truth)
 * that stores the core algorithm for analyzing and evaluating a sanity index value of vacancy :)
 */
class VesloSanityIndexingCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('veslo:sanity:indexing')
            ->setDescription('Calculates vacancy sanity index via "Ministry of Truth" API')
            ->addOption(
                'iterations',
                null,
                InputOption::VALUE_REQUIRED,
                'Maximum count of vacancies to proceed during command\'s single run',
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
        $cockroach = $container->get('veslo.sanity.index.cockroach');

        $iterations = $input->getOption('iterations');

        for ($iteration = 0; $iteration < $iterations; ++$iteration) {
            $cockroach->deliver();
        }

        $output->writeln('Indexing complete.');
    }
}
